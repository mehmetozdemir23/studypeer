<?php

use App\Models\User;

it('registers user', function () {
    $userData = [
        'firstname' => 'testfirstname',
        'lastname' => 'testlastname',
        'email' => 'testuser@example.com',
        'password' => 'Testpassword123*',
        'password_confirmation' => 'Testpassword123*',
    ];

    $response = $this->post('/register', $userData);

    $response->assertStatus(201);
    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'email' => $userData['email'],
    ]);
});

it('logs in user', function () {
    $user = User::factory()->create([
        'email' => 'testuser@example.com',
        'password' => 'testpassword',
    ]);

    $response = $this->post('/login', [
        'email' => 'testuser@example.com',
        'password' => 'testpassword',
    ]);

    $response->assertStatus(200);
    $this->assertAuthenticatedAs($user);
});

it('logs out user', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertStatus(200);
    $this->assertGuest();
});
