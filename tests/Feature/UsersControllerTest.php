<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

class UsersControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_users_index()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_access']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $response = $this->get(route('admin.users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.index');
        $response->assertViewHas('roles');
    }

    /** @test */
    public function it_displays_user_creation_form()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_create']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $response = $this->get(route('admin.users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.create');
        $response->assertViewHas('roles');
    }

    /** @test */
    public function it_stores_user()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_create']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $role = Role::create(['title' => 'UserRole']);
        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->post(route('admin.users.store'), [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'roles' => [$role->id]
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
        $this->assertDatabaseHas('role_user', [
            'user_id' => User::where('email', 'test@example.com')->first()->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function it_displays_user_edit()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $user = User::factory()->create();
        $role = Role::create(['title' => 'UserRole']);
        $user->roles()->attach($role->id);

        $response = $this->get(route('admin.users.edit', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.edit');
        $response->assertViewHas('roles');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function it_updates_user()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $user = User::factory()->create();
        $role = Role::create(['title' => 'UserRole']);
        $user->roles()->attach($role->id);

        $newRole = Role::create(['title' => 'NewUserRole']);
        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $response = $this->put(route('admin.users.update', $user), [
            'name' => 'Updated User',
            'email' => 'updated@example.com',
            'roles' => [$newRole->id]
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['email' => 'updated@example.com']);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $newRole->id,
        ]);
    }

    /** @test */
    public function it_displays_user()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_show']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $user = User::factory()->create();
        $role = Role::create(['title' => 'UserRole']);
        $user->roles()->attach($role->id);

        $response = $this->get(route('admin.users.show', $user));

        $response->assertStatus(200);
        $response->assertViewIs('admin.users.show');
        $response->assertViewHas('user', $user);
    }

    /** @test */
    public function it_deletes_user()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_delete']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $user = User::factory()->create();
        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $response = $this->delete(route('admin.users.destroy', $user));

        $this->assertSoftDeleted('users', ['id' => $user->id]);
    }

    /** @test */
    public function it_mass_deletes_users()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'user_delete']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $users = User::factory()->count(3)->create();
        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $response = $this->delete(route('admin.users.massDestroy'), [
            'ids' => $users->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        foreach ($users as $user) {
            $this->assertSoftDeleted('users', ['id' => $user->id]);
        }
    }
}
