<?php

namespace Tests\Unit;

use App\Http\Resources\Admin\UserResource;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the UserResource transforms the model correctly.
     *
     * @return void
     */
    public function testUserResource()
    {
        // Create a test user using the factory
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]);

        // Create the resource
        $resource = new UserResource($user);

        // Transform the resource into an array
        $resourceArray = $resource->toArray(request());

        // Extract only name and email fields from the resource array
        $filteredArray = [
            'name' => $resourceArray['name'],
            'email' => $resourceArray['email'],
        ];

        // Assert the transformed array matches the expected output
        $this->assertEquals([
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ], $filteredArray);
    }
}
