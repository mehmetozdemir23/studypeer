<?php
use App\Models\Group;
use App\Models\Message;
use App\Models\Permission;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    // Create permissions
    $readMessagesPermission = Permission::create(['name' => 'read messages']);
    $createMessagesPermission = Permission::create(['name' => 'create messages']);
    $updateMessagesPermission = Permission::create(['name' => 'update messages']);
    $deleteMessagesPermission = Permission::create(['name' => 'delete messages']);

    // Attach permissions to roles
    $this->attachPermissions($this->adminRole, [
        $readMessagesPermission,
        $createMessagesPermission,
        $updateMessagesPermission,
        $deleteMessagesPermission
    ]);

    $this->attachPermissions($this->tutorRole, [
        $readMessagesPermission,
        $createMessagesPermission,
        $updateMessagesPermission,
        $deleteMessagesPermission
    ]);

    $this->attachPermissions($this->studentRole, [
        $readMessagesPermission,
        $createMessagesPermission,
        $updateMessagesPermission,
        $deleteMessagesPermission
    ]);
});

// Admin permissions

it('allows an admin to read all messages', function () {
    $response = $this->actingAs($this->admin)->get('/api/messages');

    $response->assertOk();
});

it('allows an admin to read a specific message', function () {
    $message = Message::factory()->create();

    $response = $this->actingAs($this->admin)->get("/api/messages/$message->id");

    $response->assertOk()
        ->assertJsonFragment(['id' => $message->id]);
});

it('allows an admin to create a new message', function () {
    $messageData = Message::factory()->make()->toArray();

    $response = $this->actingAs($this->admin)->post('/api/messages', $messageData);

    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('messages', ['id' => $response->json()['id']]);
});

it('allows an admin to update a message they sent', function () {
    $existingMessage = Message::factory()->for($this->admin, 'sender')->create();

    $updatedMessage = [...$existingMessage->toArray(), 'content' => 'new message content'];

    $response = $this->actingAs($this->admin)->put("/api/messages/$existingMessage->id", $updatedMessage);

    $response->assertOk()
        ->assertJsonFragment(['id' => $updatedMessage['id']]);
    $this->assertDatabaseHas('messages', ['id' => $updatedMessage['id'], 'content' => $updatedMessage['content']])
        ->assertDatabaseMissing('messages', ['id' => $existingMessage->id, 'content' => $existingMessage->content]);

});

it('does not allow an admin to update a message they did not send', function () {
    $existingMessage = Message::factory()->create();

    $updatedMessage = [...$existingMessage->toArray(), 'content' => 'new message content'];

    $response = $this->actingAs($this->admin)->put("/api/messages/$existingMessage->id", $updatedMessage);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows an admin to delete a message they sent', function () {
    $message = Message::factory()->for($this->admin, 'sender')->create();

    $response = $this->actingAs($this->admin)->delete("/api/messages/{$message->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('messages', ['id' => $message->id]);
});

it('does not allow an admin to delete a message they did not send', function () {
    $message = Message::factory()->create();

    $response = $this->actingAs($this->admin)->delete("/api/messages/{$message->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

// Tutor permissions

it('allows a tutor to read messages only in groups they created or tutor', function () {
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $unrelatedGroup = Group::factory()->create();

    $messages = [
        'created' => Message::factory()->for($createdGroup, 'group')->create(),
        'tutored' => Message::factory()->for($tutoredGroup, 'group')->create(),
        'unrelated' => Message::factory()->for($unrelatedGroup, 'group')->create()
    ];

    $response = $this->actingAs($this->tutor)->get('api/messages');

    $response->assertOk()
        ->assertJsonFragment(['id' => $messages['created']->id])
        ->assertJsonFragment(['id' => $messages['tutored']->id])
        ->assertJsonMissing(['id' => $messages['unrelated']->id]);
});

it('allows a tutor to read a specific message only in a group they created or tutor', function () {
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $unrelatedGroup = Group::factory()->create();

    $messages = [
        'created' => Message::factory()->for($createdGroup, 'group')->create(),
        'tutored' => Message::factory()->for($tutoredGroup, 'group')->create(),
        'unrelated' => Message::factory()->for($unrelatedGroup, 'group')->create()
    ];

    $response = $this->actingAs($this->tutor)->get("/api/messages/{$messages['created']->id}");
    $response->assertOk()
        ->assertJsonFragment(['id' => $messages['created']->id]);

    $response = $this->actingAs($this->tutor)->get("/api/messages/{$messages['tutored']->id}");
    $response->assertOk()
        ->assertJsonFragment(['id' => $messages['tutored']->id]);

    $response = $this->actingAs($this->tutor)->get("/api/messages/{$messages['unrelated']->id}");
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to create a new message only in a group they created or tutor', function () {
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $unrelatedGroup = Group::factory()->create();

    $messages = [
        'created' => Message::factory()->for($createdGroup, 'group')->make()->toArray(),
        'tutored' => Message::factory()->for($tutoredGroup, 'group')->make()->toArray(),
        'unrelated' => Message::factory()->for($unrelatedGroup, 'group')->make()->toArray()
    ];

    $response = $this->actingAs($this->tutor)->post('/api/messages', $messages['created']);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('messages', ['id' => $response->json()['id']]);

    $response = $this->actingAs($this->tutor)->post('/api/messages', $messages['tutored']);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('messages', ['id' => $response->json()['id']]);

    $response = $this->actingAs($this->tutor)->post('/api/messages', $messages['unrelated']);
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to update a message they sent', function () {
    $existingMessage = Message::factory()->for($this->tutor, 'sender')->create();

    $updatedMessage = [...$existingMessage->toArray(), 'content' => 'new message content'];

    $response = $this->actingAs($this->tutor)->put("/api/messages/$existingMessage->id", $updatedMessage);

    $response->assertOk();
    $this->assertDatabaseHas('messages', ['id' => $updatedMessage['id'], 'content' => $updatedMessage['content']])
        ->assertDatabaseMissing('messages', ['id' => $existingMessage->id, 'content' => $existingMessage->content]);

});

it('does not allow a tutor to update a message they did not send', function () {
    $existingMessage = Message::factory()->create();

    $updatedMessage = [...$existingMessage->toArray(), 'content' => 'new message content'];

    $response = $this->actingAs($this->tutor)->put("/api/messages/$existingMessage->id", $updatedMessage);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to delete a message they sent', function () {
    $message = Message::factory()->for($this->tutor, 'sender')->create();

    $response = $this->actingAs($this->tutor)->delete("/api/messages/{$message->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('messages', ['id' => $message->id]);
});

it('does not allow a tutor to delete a message they did not send', function () {
    $message = Message::factory()->create();

    $response = $this->actingAs($this->tutor)->delete("/api/messages/{$message->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

// Student permissions

it('allows a student to read messages in groups they belong to', function () {
    $group1 = Group::factory()->create();
    $group1->members()->attach($this->student->id);
    $message1 = Message::factory()->for($group1, 'group')->create();

    $message2 = Message::factory()->create();

    $response = $this->actingAs($this->student)->get('/api/messages');

    $response->assertOk()
        ->assertJsonFragment(['id' => $message1->id])
        ->assertJsonMissing(['id' => $message2->id]);

});

it('allows a student to read a specific message in a group they belong to', function () {
    $group = Group::factory()->create();
    $group->members()->attach($this->student->id);
    $message = Message::factory()->for($group, 'group')->create();

    $response = $this->actingAs($this->student)->get("/api/messages/$message->id");

    $response->assertOk()
        ->assertJsonFragment(['id' => $message->id]);
});

it('does not allow a student to read a specifc message in a group they do not belong to', function () {
    $group = Group::factory()->create();
    $message = Message::factory()->for($group, 'group')->create();

    $response = $this->actingAs($this->student)->get("/api/messages/$message->id");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a student to create a new message in a group they belong to', function () {
    $group = Group::factory()->create();
    $group->members()->attach($this->student->id);
    $messageData = Message::factory()->for($group, 'group')->make()->toArray();

    $response = $this->actingAs($this->student)->post('/api/messages', $messageData);

    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('messages', ['id' => $response->json()['id']]);
});

it('does not allow a student to create a new message in a group they do not belong to', function () {
    $message = Message::factory()->make()->toArray();

    $response = $this->actingAs($this->student)->post('/api/messages', $message);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a student to update a message they sent', function () {
    $existingMessage = Message::factory()->for($this->student, 'sender')->create();

    $updatedMessage = [...$existingMessage->toArray(), 'content' => 'new message content'];

    $response = $this->actingAs($this->student)->put("/api/messages/$existingMessage->id", $updatedMessage);

    $response->assertOk();
    $this->assertDatabaseHas('messages', ['id' => $updatedMessage['id'], 'content' => $updatedMessage['content']])
        ->assertDatabaseMissing('messages', ['id' => $existingMessage->id, 'content' => $existingMessage->content]);
});

it('does not allow a student to update a message they did not send', function () {
    $existingMessage = Message::factory()->create();

    $updatedMessage = [...$existingMessage->toArray(), 'content' => 'new message content'];

    $response = $this->actingAs($this->student)->put("/api/messages/$existingMessage->id", $updatedMessage);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a student to delete a message they sent', function () {
    $message = Message::factory()->for($this->student, 'sender')->create();

    $response = $this->actingAs($this->student)->delete("/api/messages/{$message->id}");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('messages', ['id' => $message->id]);
});

it('does not allow a student to delete a message they did not send', function () {
    $message = Message::factory()->create();

    $response = $this->actingAs($this->student)->delete("/api/messages/{$message->id}");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});