<?php

use App\Models\Group;
use App\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    // Create permissions
    $readGroupsPermission = Permission::create(['name' => 'read groups']);
    $createGroupsPermission = Permission::create(['name' => 'create groups']);
    $updateGroupsPermission = Permission::create(['name' => 'update groups']);
    $deleteGroupsPermission = Permission::create(['name' => 'delete groups']);

    // Attach permissions to roles
    $this->attachPermissions($this->adminRole, [
        $readGroupsPermission,
        $createGroupsPermission,
        $updateGroupsPermission,
        $deleteGroupsPermission,
    ]);

    $this->attachPermissions($this->tutorRole, [
        $readGroupsPermission,
        $createGroupsPermission,
        $updateGroupsPermission,
        $deleteGroupsPermission,
    ]);

    $this->attachPermissions($this->studentRole, [
        $readGroupsPermission,
    ]);
});

// Admin permissions

it('allows an admin to read all groups', function () {
    $response = $this->actingAs($this->admin)->get('/api/groups');

    $response->assertOk();
});

it('allows an admin to read a specific group', function () {
    $group = Group::factory()->create();

    $response = $this->actingAs($this->admin)->get("/api/groups/{$group->id}");

    $response->assertOk();
});

it('allows an admin to create a new group', function () {
    $groupData = Group::factory()->for($this->admin, 'creator')->make()->toArray();

    $response = $this->actingAs($this->admin)->post('/api/groups', $groupData);

    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('groups', ['id' => $response->json()['id']]);
});

it('allows an admin to update any existing group', function () {
    $existingGroup = Group::factory()->create();

    $updatedGroup = [...$existingGroup->toArray(), 'name' => 'newgroupname'];

    $response = $this->actingAs($this->admin)->put("/api/groups/{$existingGroup->id}", $updatedGroup);

    $response->assertOk()
        ->assertJsonFragment(['id' => $updatedGroup['id']]);
    $this->assertDatabaseHas('groups', ['id' => $updatedGroup['id'], 'name' => $updatedGroup['name']])
        ->assertDatabaseMissing('groups', ['id' => $existingGroup['id'], 'name' => $existingGroup['name']]);
});

it('allows an admin to delete any group', function () {
    $group = Group::factory()->create();

    $response = $this->actingAs($this->admin)->delete("/api/groups/{$group->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('groups', ['id' => $group->id]);
});

// Tutor permissions

it('allows a tutor to read all groups', function () {
    $response = $this->actingAs($this->tutor)->get('/api/groups');

    $response->assertOk();
});

it('allows a tutor to read a specific group', function () {
    $group = Group::factory()->create();

    $response = $this->actingAs($this->tutor)->get("/api/groups/{$group->id}");

    $response->assertOk();
});

it('allows a tutor to create a new group', function () {
    $groupData = Group::factory()->for($this->tutor, 'creator')->make()->toArray();

    $response = $this->actingAs($this->tutor)->post('/api/groups', $groupData);

    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('groups', ['id' => $response->json()['id']]);
});

it('allows a tutor to update a group they created', function () {
    $existingGroup = Group::factory()->for($this->tutor, 'creator')->create();

    $updatedGroup = [...$existingGroup->toArray(), 'name' => 'newgroupname'];

    $response = $this->actingAs($this->tutor)->put("/api/groups/{$existingGroup->id}", $updatedGroup);

    $response->assertOk();
    $response->assertJsonFragment(['id' => $updatedGroup['id']]);
    $this->assertDatabaseHas('groups', ['id' => $updatedGroup['id'], 'name' => $updatedGroup['name']])
        ->assertDatabaseMissing('groups', ['id' => $existingGroup['id'], 'name' => $existingGroup['name']]);

});

it('does not allow a tutor to update a group they did not create', function () {
    $existingGroup = Group::factory()->create();

    $updatedGroup = [...$existingGroup->toArray(), 'name' => 'newgroupname'];

    $response = $this->actingAs($this->tutor)->put("/api/groups/{$existingGroup->id}", $updatedGroup);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to delete a group they created', function () {
    $group = Group::factory()->for($this->tutor, 'creator')->create();

    $response = $this->actingAs($this->tutor)->delete("/api/groups/{$group->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('groups', ['id' => $group->id]);
});

it('does not allow a tutor to delete a group they did not create', function () {
    $group = Group::factory()->create();

    $response = $this->actingAs($this->tutor)->delete("/api/groups/{$group->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

// Student permissions

it('allows a student to read groups they belong to', function () {
    $group1 = Group::factory()->create();
    $group2 = Group::factory()->create();
    $group3 = Group::factory()->create();

    $group1->members()->attach($this->student->id);
    $group2->members()->attach($this->student->id);

    $response = $this->actingAs($this->student)->get('/api/groups');

    $response->assertOk();
    $response->assertJsonFragment(['id' => $group1->id]);
    $response->assertJsonFragment(['id' => $group2->id]);
    $response->assertJsonMissing(['id' => $group3->id]);
});

it('allows a student to read a specific group they belong to', function () {
    $group = Group::factory()->create();

    $group->members()->attach($this->student->id);

    $response = $this->actingAs($this->student)->get("/api/groups/{$group->id}");

    $response->assertOk();
    $response->assertJsonFragment(['id' => $group->id]);
});

it("does not allow a student to read a specific group they do not belong to", function () {
    $group = Group::factory()->create();

    $response = $this->actingAs($this->student)->get("/api/groups/{$group->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a student to create a new group', function () {
    $groupData = Group::factory()->for($this->student, 'creator')->make()->toArray();

    $response = $this->actingAs($this->student)->post('/api/groups', $groupData);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a student to update any existing group', function () {
    $existingGroup = Group::factory()->create();

    $updatedGroup = [...$existingGroup->toArray(), 'name' => 'newgroupname'];

    $response = $this->actingAs($this->student)->put("/api/groups/{$existingGroup->id}", $updatedGroup);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a student to delete any group', function () {
    $group = Group::factory()->create();

    $response = $this->actingAs($this->student)->delete("/api/groups/{$group->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});
