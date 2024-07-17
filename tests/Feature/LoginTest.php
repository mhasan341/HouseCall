<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt($password = 'password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@gmail.com',
            'password' => $password,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Successfully logged in',
        ]);
        $response->assertJsonStructure([
            'status',
            'message',
            'access_token',
        ]);
    }

    /** @test */
    public function login_fails_with_invalid_credentials()
    {
        // Create a user
        User::factory()->create([
            'email' => 'johndoe@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@gmail.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422);
        $response->assertJson([
            'status' => false,
            'message' => 'The given input doesn\'t match with our records',
        ]);
    }
}
