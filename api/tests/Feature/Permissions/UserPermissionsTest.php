<?php

use App\Models\Group;
use App\Models\Permission;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    // Create permissions
    $readUsersPermission = Permission::create(['name' => 'read users']);
    $createUsersPermission = Permission::create(['name' => 'create users']);
    $updateUsersPermission = Permission::create(['name' => 'update users']);
    $deleteUsersPermission = Permission::create(['name' => 'delete users']);

    // Attach permissions to roles
    $this->attachPermissions($this->adminRole, [
        $readUsersPermission,
        $createUsersPermission,
        $updateUsersPermission,
        $deleteUsersPermission,
    ]);

    $this->attachPermissions($this->tutorRole, [
        $readUsersPermission,
        $updateUsersPermission,
    ]);

    $this->attachPermissions($this->studentRole, [
        $readUsersPermission,
        $updateUsersPermission,
    ]);
});

// Admin permissions

it('allows an admin to read all users', function () {
    $response = $this->actingAs($this->admin)->get('/api/users');

    $response->assertOk();
});

it('allows an admin to read any specific user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->admin)->get("/api/users/{$user->id}");

    $response->assertOk();
});

it('allows an admin to create a new user', function () {
    $userData = [
        ...User::factory()->make()->toArray(),
        'password' => 'testpassword',
        'password_confirmation' => 'testpassword',
    ];

    $response = $this->actingAs($this->admin)->post('/api/users', $userData);

    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('users', ['id' => $response->json()['id']]);
});

it('allows an admin to update any existing user', function () {
    $existingUser = User::factory()->create();

    $updatedUser = [...$existingUser->toArray(), 'firstname' => 'newfirstname'];

    $response = $this->actingAs($this->admin)->put("/api/users/{$existingUser->id}", $updatedUser);

    $response->assertOk()
        ->assertJsonFragment(['id' => $updatedUser['id']]);
    $this->assertDatabaseHas('users', ['id' => $updatedUser['id'], 'firstname' => $updatedUser['firstname']])
        ->assertDatabaseMissing('users', ['id' => $existingUser['id'], 'firstname' => $existingUser['firstname']]);
});

it('allows an admin to delete any user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->admin)->delete("/api/users/{$user->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

// Tutor permissions

it('allows a tutor to read users they tutor', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $user3 = User::factory()->create();

    $group = Group::factory()->for(User::factory(), 'creator')->create();
    $group->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $group->members()->attach([$user1->id, $user2->id]);

    $response = $this->actingAs($this->tutor)->get('/api/users');

    $response->assertOk();
    $response->assertJsonFragment(['id' => $user1->id]);
    $response->assertJsonFragment(['id' => $user2->id]);
    $response->assertJsonMissing(['id' => $user3->id]);
});

it('allows a tutor to read a specific user they tutor', function () {
    $user = User::factory()->create();

    $group = Group::factory()->for(User::factory(), 'creator')->create();
    $group->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $group->members()->attach($user->id);

    $response = $this->actingAs($this->tutor)->get("/api/users/{$user->id}");

    $response->assertOk();
});

it('does not allow a tutor to read a specific user they do not tutor', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->tutor)->get("/api/users/{$user->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a tutor to create a new user', function () {
    $userData = [
        ...User::factory()->make()->toArray(),
        'password' => 'testpassword',
        'password_confirmation' => 'testpassword',
    ];

    $response = $this->actingAs($this->tutor)->post('/api/users', $userData);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to update a user they tutor', function () {
    $existingUser = User::factory()->create();

    $group = Group::factory()->for(User::factory(), 'creator')->create();
    $group->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $group->members()->attach($existingUser->id);

    $updatedUser = [...$existingUser->toArray(), 'firstname' => 'newfirstname'];

    $response = $this->actingAs($this->tutor)->put("/api/users/{$existingUser->id}", $updatedUser);

    $response->assertOk()
        ->assertJsonFragment(['id' => $updatedUser['id']]);
    $this->assertDatabaseHas('users', ['id' => $updatedUser['id'], 'firstname' => $updatedUser['firstname']])
        ->assertDatabaseMissing('users', ['id' => $existingUser['id'], 'firstname' => $existingUser['firstname']]);
});

it('does not allow a tutor to update a user they do not tutor', function () {
    $existingUser = User::factory()->create();

    $updatedUser = [...$existingUser->toArray(), 'firstname' => 'newfirstname'];

    $response = $this->actingAs($this->tutor)->put("/api/users/{$existingUser->id}", $updatedUser);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a tutor to delete any user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->tutor)->delete("/api/users/{$user->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

// Student permissions

it('allows a student to read users in the same group', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();

    $group = Group::factory()->for(User::factory(), 'creator')->create();
    $group->members()->attach([$this->student->id, $user1->id]);

    $response = $this->actingAs($this->student)->get('/api/users');

    $response->assertOk();
    $response->assertJsonFragment(['id' => $user1->id]);
    $response->assertJsonMissing(['id' => $user2->id]);
});

it('does not allow a student to read a specific user not in the same group', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->student)->get("/api/users/{$user->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a student to create a new user', function () {
    $userData = [
        ...User::factory()->make()->toArray(),
        'password' => 'testpassword',
        'password_confirmation' => 'testpassword',
    ];

    $response = $this->actingAs($this->student)->post('/api/users', $userData);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a student to update any existing user', function () {
    $existingUser = User::factory()->create();

    $updatedUser = [...$existingUser->toArray(), 'firstname' => 'newfirstname'];

    $response = $this->actingAs($this->student)->put("/api/users/{$existingUser->id}", $updatedUser);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a student to delete any user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($this->student)->delete("/api/users/{$user->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});
