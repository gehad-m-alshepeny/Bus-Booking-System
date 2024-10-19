<?php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create a user for login
    $this->user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'), 
    ]);
});

it('allows a user to log in with valid credentials', function () {

    $credentials = [
        'email' => 'test@example.com',
        'password' => 'password123', 
    ];

    $response = $this->postJson('/api/auth/login', $credentials);

    $response->assertStatus(200)
             ->assertJsonStructure([
                     'token',
                     'user' => [
                         'id',
                         'name',
                         'email',
                     ],
             ]);
});

it('prevents login with invalid credentials', function () {

    $credentials = [
        'email' => 'test@example.com',
        'password' => 'wrongpassword', // Incorrect password
    ];

    $response = $this->postJson('/api/auth/login', $credentials);

    $response->assertStatus(401)
             ->assertJson([
                 'message' => 'Invalid credentials',
             ]);
});
