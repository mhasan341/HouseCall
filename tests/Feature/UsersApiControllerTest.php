<?php

namespace Tests\Feature;

use App\Models\Permission;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

class UsersApiControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_retrieve_all_users()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_access']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Create users with roles
        $users = User::factory()->count(3)->create()->each(function ($user) {
            $role = Role::factory()->create();
            $user->roles()->attach($role);
        });

        // Send the request to retrieve all users
        $response = $this->getJson(route('api.users.index'));

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'email',
                    'roles' => [
                        '*' => ['id', 'title']
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function can_store_user()
    {
        // Create the Admin role
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_create']);
        $adminRole->permissions()->attach($permission->id);
        // Create a user with the Admin role
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);

        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Prepare user data with roles
        $userData = User::factory()->make()->toArray();
        $userData['password'] = 'password';
        $userData['roles'] = [2];

        // Send the request to store a new user
        $response = $this->postJson(route('api.users.store'), $userData);

        // Assert the response status and structure
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(['name' => $userData['name']]);

        // Assert the user roles are stored correctly
        $this->assertDatabaseHas('role_user', [
            'role_id' => [2],
            'user_id' => $response->json('data.id')
        ]);
    }

    /** @test */
    public function can_show_user()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_show']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Create a user with roles
        $user = User::factory()->create();
        $role = Role::factory()->create();
        $user->roles()->attach($role);

        // Send the request to show the user
        $response = $this->getJson(route('api.users.show', $user->id));

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'roles' => [
                    '*' => ['id', 'title']
                ]
            ]
        ]);
    }

    /** @test */
    public function can_update_user()
    {
        // Create the Admin
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Prepare user data
        $user = User::factory()->create();
        $role = Role::factory()->create();
        // Prepare updated user data with new roles
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updatedemail@example.com',
            'roles' => [$role->id],
        ];

        // Send the request to update the user
        $response = $this->putJson(route('api.users.update', $user), $updatedData);
        //var_dump('RESPONSE', $user->id);
        // Assert the response status and structure
        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJsonFragment(['name' => $updatedData['name']]);

        // Assert the user roles are updated correctly
        $this->assertDatabaseHas('role_user', [
            'role_id' => $role->id,
            'user_id' => $user->id
        ]);
    }

    /** @test */
    public function can_delete_user()
    {
        // Create the Admin
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_delete']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Create a user
        $user = User::factory()->create();

        // Send the request to delete the user
        $response = $this->deleteJson(route('api.users.destroy', $user));

        // Assert the response status
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        // Assert the user is deleted
        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }
}

