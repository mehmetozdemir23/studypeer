<?php

use App\Models\Group;
use App\Models\Permission;
use App\Models\StudySession;
use Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    // Create permissions
    $readStudySessionsPermission = Permission::create(['name' => 'read study sessions']);
    $createStudySessionsPermission = Permission::create(['name' => 'create study sessions']);
    $updateStudySessionsPermission = Permission::create(['name' => 'update study sessions']);
    $deleteStudySessionsPermission = Permission::create(['name' => 'delete study sessions']);

    // Attach permissions to roles
    $this->attachPermissions($this->adminRole, [
        $readStudySessionsPermission,
        $createStudySessionsPermission,
        $updateStudySessionsPermission,
        $deleteStudySessionsPermission
    ]);

    $this->attachPermissions($this->tutorRole, [
        $readStudySessionsPermission,
        $createStudySessionsPermission,
        $updateStudySessionsPermission,
        $deleteStudySessionsPermission
    ]);

    $this->attachPermissions($this->studentRole, [
        $readStudySessionsPermission,
    ]);
});

// Admin permissions

it('allows an admin to read all study sessions', function () {
    $response = $this->actingAs($this->admin)->get('/api/study-sessions');

    $response->assertOk();
});

it('allows an admin to read a specific study session', function () {
    $studySession = StudySession::factory()->create();

    $response = $this->actingAs($this->admin)->get("/api/study-sessions/$studySession->id");

    $response->assertOk()
        ->assertJsonFragment(['id' => $studySession->id]);
});

it('allows an admin to create a new study session', function () {
    $studySessionData = StudySession::factory()->make()->toArray();

    $response = $this->actingAs($this->admin)->post('/api/study-sessions', $studySessionData);

    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('study_sessions', ['id' => $response->json()['id']]);
});

it('allows an admin to update any existing study session', function () {
    $existingStudySession = StudySession::factory()->create();

    $updatedStudySession = [...$existingStudySession->toArray(), 'title' => 'newstudysessiontitle'];

    $response = $this->actingAs($this->admin)->put("/api/study-sessions/$existingStudySession->id", $updatedStudySession);

    $response->assertOk()
        ->assertJsonFragment(['id' => $updatedStudySession['id']]);
    $this->assertDatabaseHas('study_sessions', ['id' => $updatedStudySession['id'], 'title' => $updatedStudySession['title']])
        ->assertDatabaseMissing('study_sessions', ['id' => $existingStudySession->id, 'title' => $existingStudySession->title]);
});

it('allows an admin to delete any study session', function () {
    $studySession = StudySession::factory()->create();

    $response = $this->actingAs($this->admin)->delete("/api/study-sessions/$studySession->id");

    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('study_sessions', ['id' => $studySession->id]);
});

// Tutor permissions

it('allows a tutor to read study sessions only in groups they created or tutor', function () {
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $unrelatedGroup = Group::factory()->create();

    $studySessions = [
        'created' => StudySession::factory()->for($createdGroup, 'group')->create(),
        'tutored' => StudySession::factory()->for($tutoredGroup, 'group')->create(),
        'unrelated' => StudySession::factory()->for($unrelatedGroup, 'group')->create()
    ];

    $response = $this->actingAs($this->tutor)->get("/api/study-sessions");

    $response->assertOk()
        ->assertJsonFragment(['id' => $studySessions['created']->id])
        ->assertJsonFragment(['id' => $studySessions['tutored']->id])
        ->assertJsonMissing(['id' => $studySessions['unrelated']->id]);
});

it('allows a tutor to read a specific study session only in a group they created or tutor', function () {
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $unrelatedGroup = Group::factory()->create();

    $studySessions = [
        'created' => StudySession::factory()->for($createdGroup, 'group')->create(),
        'tutored' => StudySession::factory()->for($tutoredGroup, 'group')->create(),
        'unrelated' => StudySession::factory()->for($unrelatedGroup, 'group')->create()
    ];

    $response = $this->actingAs($this->tutor)->get("/api/study-sessions/{$studySessions['created']->id}");
    $response->assertOk()
        ->assertJsonFragment(['id' => $studySessions['created']->id]);

    $response = $this->actingAs($this->tutor)->get("/api/study-sessions/{$studySessions['tutored']->id}");
    $response->assertOk()
        ->assertJsonFragment(['id' => $studySessions['tutored']->id]);

    $response = $this->actingAs($this->tutor)->get("/api/study-sessions/{$studySessions['unrelated']->id}");
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to create a new study session only in a group they created or tutor', function () {
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $unrelatedGroup = Group::factory()->create();

    $studySessions = [
        'created' => StudySession::factory()->for($createdGroup, 'group')->make()->toArray(),
        'tutored' => StudySession::factory()->for($tutoredGroup, 'group')->make()->toArray(),
        'unrelated' => StudySession::factory()->for($unrelatedGroup, 'group')->make()->toArray()
    ];

    $response = $this->actingAs($this->tutor)->post('/api/study-sessions', $studySessions['created']);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('study_sessions', ['id' => $response->json()['id']]);

    $response = $this->actingAs($this->tutor)->post('/api/study-sessions', $studySessions['tutored']);
    $response->assertStatus(Response::HTTP_CREATED);
    $this->assertDatabaseHas('study_sessions', ['id' => $response->json()['id']]);

    $response = $this->actingAs($this->tutor)->post('/api/study-sessions', $studySessions['unrelated']);
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('allows a tutor to update any existing study session only in a group they created or tutor', function () {
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $unrelatedGroup = Group::factory()->create();

    $existingStudySessions = [
        'created' => StudySession::factory()->for($createdGroup, 'group')->create(),
        'tutored' => StudySession::factory()->for($tutoredGroup, 'group')->create(),
        'unrelated' => StudySession::factory()->for($unrelatedGroup, 'group')->create()
    ];

    $updatedStudySessions = [
        'created' => [...$existingStudySessions['created']->toArray(), 'title' => 'newstudysessiontitle1'],
        'tutored' => [...$existingStudySessions['tutored']->toArray(), 'title' => 'newstudysessiontitle2'],
        'unrelated' => [...$existingStudySessions['unrelated']->toArray(), 'title' => 'newstudysessiontitle3']
    ];

    $response = $this->actingAs($this->tutor)->put("/api/study-sessions/{$existingStudySessions['created']->id}", $updatedStudySessions['created']);
    $response->assertOk();
    $this->assertDatabaseHas('study_sessions', ['id' => $updatedStudySessions['created']['id'], 'title' => $updatedStudySessions['created']['title']])
        ->assertDatabaseMissing('study_sessions', ['id' => $existingStudySessions['created']->id, 'title' => $existingStudySessions['created']->title]);

    $response = $this->actingAs($this->tutor)->put("/api/study-sessions/{$existingStudySessions['tutored']->id}", $updatedStudySessions['tutored']);
    $response->assertOk();
    $this->assertDatabaseHas('study_sessions', ['id' => $updatedStudySessions['tutored']['id'], 'title' => $updatedStudySessions['tutored']['title']])
        ->assertDatabaseMissing('study_sessions', ['id' => $existingStudySessions['tutored']->id, 'title' => $existingStudySessions['tutored']->title]);

    $response = $this->actingAs($this->tutor)->put("/api/study-sessions/{$existingStudySessions['unrelated']->id}", $updatedStudySessions['unrelated']);
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});


it('allows a tutor to delete a study session only in a group they created or tutor', function () {
    $createdGroup = Group::factory()->for($this->tutor, 'creator')->create();
    $tutoredGroup = Group::factory()->create();
    $tutoredGroup->tutors()->attach($this->tutor->id, ['role' => 'tutor']);
    $unrelatedGroup = Group::factory()->create();

    $studySessions = [
        'created' => StudySession::factory()->for($createdGroup, 'group')->create(),
        'tutored' => StudySession::factory()->for($tutoredGroup, 'group')->create(),
        'unrelated' => StudySession::factory()->for($unrelatedGroup, 'group')->create()
    ];

    $response = $this->actingAs($this->tutor)->delete("/api/study-sessions/{$studySessions['created']->id}");
    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('study_sessions', ['id' => $studySessions['created']->id]);

    $response = $this->actingAs($this->tutor)->delete("/api/study-sessions/{$studySessions['tutored']->id}");
    $response->assertStatus(Response::HTTP_NO_CONTENT);
    $this->assertDatabaseMissing('study_sessions', ['id' => $studySessions['tutored']->id]);

    $response = $this->actingAs($this->tutor)->delete("/api/study-sessions/{$studySessions['unrelated']->id}");
    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

// Student permissions

it('allows a student to read study sessions in groups they belong to', function () {
    $group1 = Group::factory()->create();
    $group1->members()->attach($this->student->id);
    $studySession1 = StudySession::factory()->for($group1, 'group')->create();

    $studySession2 = StudySession::factory()->create();

    $response = $this->actingAs($this->student)->get('/api/study-sessions');

    $response->assertOk()
        ->assertJsonFragment(['id' => $studySession1->id])
        ->assertJsonMissing(['id' => $studySession2->id]);

});

it('allows a student to read a specific study session in a group they belong to', function () {
    $group = Group::factory()->create();
    $group->members()->attach($this->student->id);
    $studySession = StudySession::factory()->for($group, 'group')->create();

    $response = $this->actingAs($this->student)->get("/api/study-sessions/$studySession->id");

    $response->assertOk()
        ->assertJsonFragment(['id' => $studySession->id]);
});

it('does not allow a student to read a specifc study session in a group they do not belong to', function () {
    $group = Group::factory()->create();
    $studySession = StudySession::factory()->for($group, 'group')->create();

    $response = $this->actingAs($this->student)->get("/api/study-sessions/$studySession->id");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a student to create a new study session', function () {
    $studySessionData = StudySession::factory()->make()->toArray();

    $response = $this->actingAs($this->student)->post('/api/study-sessions', $studySessionData);

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});

it('does not allow a student to delete any existing study session', function () {
    $studySession = StudySession::factory()->create();

    $response = $this->actingAs($this->student)->delete("/api/study-sessions/$studySession->id");

    $response->assertStatus(Response::HTTP_FORBIDDEN);
});