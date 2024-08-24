<?php

use App\Models\Group;
use App\Models\Permission;
use App\Models\SharedFile;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {

    // Create permissions
    $readSharedFilesPermission = Permission::create(['name' => 'read shared files']);
    $createSharedFilesPermission = Permission::create(['name' => 'create shared files']);
    $updateSharedFilesPermission = Permission::create(['name' => 'update shared files']);
    $deleteSharedFilesPermission = Permission::create(['name' => 'delete shared files']);

    // Attach permissions to roles
    $this->attachPermissions($this->adminRole, [
        $readSharedFilesPermission,
        $createSharedFilesPermission,
        $updateSharedFilesPermission,
        $deleteSharedFilesPermission,
    ]);

    $this->attachPermissions($this->tutorRole, [
        $readSharedFilesPermission,
        $createSharedFilesPermission,
        $updateSharedFilesPermission,
        $deleteSharedFilesPermission,
    ]);

    $this->attachPermissions($this->studentRole, [
        $readSharedFilesPermission,
        $createSharedFilesPermission,
        $updateSharedFilesPermission,
        $deleteSharedFilesPermission,
    ]);
});

// Admin permissions

it('allows an admin to read all shared files', function () {
    $response = $this->actingAs($this->admin)->get('/api/shared-files');

    $response->assertOk();
});

it('allows an admin to read a specific shared file', function () {
    $sharedFile = SharedFile::factory()->create();

    $response = $this->actingAs($this->admin)->get("/api/shared-files/$sharedFile->id");

    $response->assertOk()
        ->assertJsonFragment(['id' => $sharedFile->id]);
});

it('allows an admin to create a new shared file', function () {
    Storage::fake();

    $sharedFileData = SharedFile::factory()->make()->toArray();
    $sharedFileData['file'] = UploadedFile::fake()->create(Str::random(), 200, 'pdf');

    $response = $this->actingAs($this->admin)->post('/api/shared-files', $sharedFileData);

    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('shared_files', ['id' => $response->json()['id']]);
    Storage::assertExists("{$response->json()['file_path']}");
});

it('allows an admin to update a shared file they uploaded', function () {
    $existingSharedFile = SharedFile::factory()->for($this->admin, 'uploader')->create();

    $updatedSharedFile = [...$existingSharedFile->toArray(), 'name' => 'new shared file name'];

    $response = $this->actingAs($this->admin)->put("/api/shared-files/$existingSharedFile->id", $updatedSharedFile);

    $response->assertOk()
        ->assertJsonFragment(['id' => $updatedSharedFile['id']]);
    $this->assertDatabaseHas('shared_files', ['id' => $updatedSharedFile['id'], 'name' => $updatedSharedFile['name']])
        ->assertDatabaseMissing('shared_files', ['id' => $existingSharedFile->id, 'name' => $existingSharedFile->name]);
});

it('does not allow an admin to update a shared file they did not upload', function () {
    $existingSharedFile = SharedFile::factory()->create();

    $updatedSharedFile = [...$existingSharedFile->toArray(), 'name' => 'new shared file name'];

    $response = $this->actingAs($this->admin)->put("/api/shared-files/$existingSharedFile->id", $updatedSharedFile);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows an admin to delete a shared file they uploaded', function () {
    Storage::fake();
    $sharedFile = SharedFile::factory()->for($this->admin, 'uploader')
        ->create([
            'file_path' => UploadedFile::fake()->create(Str::random(), 200, 'pdf')->store('shared_files'),
        ]);

    $response = $this->actingAs($this->admin)->delete("/api/shared-files/{$sharedFile->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('shared_files', ['id' => $sharedFile->id]);
    Storage::assertMissing($sharedFile->file_path);
});

it('does not allow an admin to delete a shared file they did not upload', function () {
    $sharedFile = SharedFile::factory()->create();

    $response = $this->actingAs($this->admin)->delete("/api/shared-files/{$sharedFile->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

// Tutor permissions

it('allows a tutor to read shared files only in groups they created or tutor', function () {
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $unrelatedGroup = Group::factory()->create();

    $sharedFiles = [
        'created' => SharedFile::factory()->for($createdGroup, 'group')->create(),
        'tutored' => SharedFile::factory()->for($tutoredGroup, 'group')->create(),
        'unrelated' => SharedFile::factory()->for($unrelatedGroup, 'group')->create(),
    ];

    $response = $this->actingAs($this->tutor)->get('api/shared-files');

    $response->assertOk()
        ->assertJsonFragment(['id' => $sharedFiles['created']->id])
        ->assertJsonFragment(['id' => $sharedFiles['tutored']->id])
        ->assertJsonMissing(['id' => $sharedFiles['unrelated']->id]);
});

it('allows a tutor to read a specific shared file only in a group they created or tutor', function () {
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $unrelatedGroup = Group::factory()->create();

    $sharedFiles = [
        'created' => SharedFile::factory()->for($createdGroup, 'group')->create(),
        'tutored' => SharedFile::factory()->for($tutoredGroup, 'group')->create(),
        'unrelated' => SharedFile::factory()->for($unrelatedGroup, 'group')->create(),
    ];

    $response = $this->actingAs($this->tutor)->get("/api/shared-files/{$sharedFiles['created']->id}");
    $response->assertOk()
        ->assertJsonFragment(['id' => $sharedFiles['created']->id]);

    $response = $this->actingAs($this->tutor)->get("/api/shared-files/{$sharedFiles['tutored']->id}");
    $response->assertOk()
        ->assertJsonFragment(['id' => $sharedFiles['tutored']->id]);

    $response = $this->actingAs($this->tutor)->get("/api/shared-files/{$sharedFiles['unrelated']->id}");
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to create a new shared file only in a group they created or tutor', function () {
    Storage::fake();

    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $unrelatedGroup = Group::factory()->create();

    $sharedFiles = [
        'created' => [...SharedFile::factory()->for($createdGroup, 'group')->make()->toArray(), 'file' => UploadedFile::fake()->create(Str::random(), 200, 'pdf')],
        'tutored' => [...SharedFile::factory()->for($tutoredGroup, 'group')->make()->toArray(), 'file' => UploadedFile::fake()->create(Str::random(), 200, 'pdf')],
        'unrelated' => [...SharedFile::factory()->for($unrelatedGroup, 'group')->make()->toArray(), 'file' => UploadedFile::fake()->create(Str::random(), 200, 'pdf')],
    ];

    $response = $this->actingAs($this->tutor)->post('/api/shared-files', $sharedFiles['created']);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('shared_files', ['id' => $response->json()['id']]);

    $response = $this->actingAs($this->tutor)->post('/api/shared-files', $sharedFiles['tutored']);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('shared_files', ['id' => $response->json()['id']]);

    $response = $this->actingAs($this->tutor)->post('/api/shared-files', $sharedFiles['unrelated']);
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to update a shared file they updated', function () {
    $existingSharedFile = SharedFile::factory()->for($this->tutor, 'uploader')->create();

    $updatedSharedFile = [...$existingSharedFile->toArray(), 'name' => 'new shared file name'];

    $response = $this->actingAs($this->tutor)->put("/api/shared-files/$existingSharedFile->id", $updatedSharedFile);

    $response->assertOk();
    $this->assertDatabaseHas('shared_files', ['id' => $updatedSharedFile['id'], 'name' => $updatedSharedFile['name']])
        ->assertDatabaseMissing('shared_files', ['id' => $existingSharedFile->id, 'name' => $existingSharedFile->name]);
});

it('does not allow a tutor to update a shared file they did not upload', function () {
    $existingSharedFile = SharedFile::factory()->create();

    $updatedSharedFile = [...$existingSharedFile->toArray(), 'name' => 'new shared file name'];

    $response = $this->actingAs($this->tutor)->put("/api/shared-files/$existingSharedFile->id", $updatedSharedFile);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to delete a shared file they uploaded', function () {
    Storage::fake();

    $sharedFile = SharedFile::factory()->for($this->tutor, 'uploader')
        ->create([
            'file_path' => UploadedFile::fake()->create(Str::random(), 200, 'pdf')->store('shared_files'),
        ]);

    $response = $this->actingAs($this->tutor)->delete("/api/shared-files/{$sharedFile->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('shared_files', ['id' => $sharedFile->id]);
    Storage::assertMissing($sharedFile->file_path);
});

it('does not allow a tutor to delete a shared file they did not upload', function () {
    $sharedFile = SharedFile::factory()->create();

    $response = $this->actingAs($this->tutor)->delete("/api/shared-files/{$sharedFile->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

// Student permissions

it('allows a student to read shared files in groups they belong to', function () {
    $group1 = Group::factory()->create();
    $group1->members()->attach($this->student->id);
    $sharedFile1 = SharedFile::factory()->for($group1, 'group')->create();

    $sharedFile2 = SharedFile::factory()->create();

    $response = $this->actingAs($this->student)->get('/api/shared-files');

    $response->assertOk()
        ->assertJsonFragment(['id' => $sharedFile1->id])
        ->assertJsonMissing(['id' => $sharedFile2->id]);
});

it('allows a student to read a specific shared file in a group they belong to', function () {
    $group = Group::factory()->create();
    $group->members()->attach($this->student->id);
    $sharedFile = SharedFile::factory()->for($group, 'group')->create();

    $response = $this->actingAs($this->student)->get("/api/shared-files/$sharedFile->id");

    $response->assertOk()
        ->assertJsonFragment(['id' => $sharedFile->id]);
});

it('does not allow a student to read a specifc shared file in a group they do not belong to', function () {
    $group = Group::factory()->create();
    $sharedFile = SharedFile::factory()->for($group, 'group')->create();

    $response = $this->actingAs($this->student)->get("/api/shared-files/$sharedFile->id");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a student to create a new shared file in a group they belong to', function () {
    Storage::fake();

    $group = Group::factory()->create();
    $group->members()->attach($this->student->id);
    $sharedFileData = SharedFile::factory()->for($group, 'group')->make()->toArray();
    $sharedFileData['file'] = UploadedFile::fake()->create(Str::random(), 200, 'pdf');

    $response = $this->actingAs($this->student)->post('/api/shared-files', $sharedFileData);

    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('shared_files', ['id' => $response->json()['id']]);
    Storage::assertExists("{$response->json()['file_path']}");
});

it('does not allow a student to create a new shared file in a group they do not belong to', function () {
    $sharedFileData = SharedFile::factory()->make()->toArray();
    $sharedFileData['file'] = UploadedFile::fake()->create(Str::random(), 200, 'pdf');

    $response = $this->actingAs($this->student)->post('/api/shared-files', $sharedFileData);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a student to update a shared file they uplaoded', function () {
    $existingSharedFile = SharedFile::factory()->for($this->student, 'uploader')->create();

    $updatedSharedFile = [...$existingSharedFile->toArray(), 'name' => 'new shared file name'];

    $response = $this->actingAs($this->student)->put("/api/shared-files/$existingSharedFile->id", $updatedSharedFile);

    $response->assertOk();
    $this->assertDatabaseHas('shared_files', ['id' => $updatedSharedFile['id'], 'name' => $updatedSharedFile['name']])
        ->assertDatabaseMissing('shared_files', ['id' => $existingSharedFile->id, 'name' => $existingSharedFile->name]);
});

it('does not allow a student to update a shared file they did not upload', function () {
    $existingSharedFile = SharedFile::factory()->create();

    $updatedSharedFile = [...$existingSharedFile->toArray(), 'namet' => 'new shared file name'];

    $response = $this->actingAs($this->student)->put("/api/shared-files/$existingSharedFile->id", $updatedSharedFile);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a student to delete a shared file they uploaded', function () {
    Storage::fake();

    $sharedFile = SharedFile::factory()->for($this->student, 'uploader')
        ->create([
            'file_path' => UploadedFile::fake()->create(Str::random(), 200, 'pdf')->store('shared_files'),
        ]);

    $response = $this->actingAs($this->student)->delete("/api/shared-files/{$sharedFile->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('shared_files', ['id' => $sharedFile->id]);
    Storage::assertMissing($sharedFile->file_path);
});

it('does not allow a student to delete a shared file they did not upload', function () {
    $sharedFile = SharedFile::factory()->create();

    $response = $this->actingAs($this->student)->delete("/api/shared-files/{$sharedFile->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});
