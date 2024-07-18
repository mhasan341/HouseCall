<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InvalidEndpointTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_returns_404_for_invalid_endpoint()
    {
        $response = $this->getJson('/api/invalid-endpoint');

        $response->assertStatus(404);
        $response->assertJson([
            'status' => false,
            'message' => 'Endpoint not found',
        ]);
    }

    /** @test */
    public function it_returns_405_for_invalid_method()
    {
        // Assuming the register endpoint only supports POST method
        $response = $this->getJson('/api/register');

        $response->assertStatus(405);
        $response->assertJson([
            'status' => false,
            'message' => 'Method not allowed',
        ]);
    }
}
