<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'message' => 'Successfully registered',
        ]);
        $response->assertJsonStructure([
            'status',
            'message',
            'access_token',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@gmail.com',
        ]);
    }

    /** @test */
    public function registration_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Jo',
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'message' => 'An error occurred',
        ]);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors' => [
                'name',
                'email',
                'password',
            ],
        ]);
    }
}
