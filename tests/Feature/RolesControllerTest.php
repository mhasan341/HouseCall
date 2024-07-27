<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

class RolesControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_roles_index()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'role_access']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $response = $this->get(route('admin.roles.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.index');
        $response->assertViewHas('roles');
    }

    /** @test */
    public function it_displays_role_creation_form()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'role_create']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $response = $this->get(route('admin.roles.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.create');
        $response->assertViewHas('permissions');
    }

    /** @test */
    public function it_stores_role()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'role_create']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $permission = Permission::create(['name' => 'create_permission']);
        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->post(route('admin.roles.store'), [
            'title' => 'new_role',
            'permissions' => [$permission->id]
        ]);


        $this->assertDatabaseHas('roles', ['title' => 'new_role']);
        $this->assertDatabaseHas('permission_role', [
            'role_id' => Role::where('title', 'new_role')->first()->id,
            'permission_id' => $permission->id,
        ]);
    }

    /** @test */
    public function it_displays_role_edit_form()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'role_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $role = Role::create(['name' => 'edit_role']);
        $permission = Permission::create(['name' => 'edit_permission']);
        $role->permissions()->sync($permission->id);

        $response = $this->get(route('admin.roles.edit', $role));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.edit');
        $response->assertViewHas('permissions');
        $response->assertViewHas('role', $role);
    }

    /** @test */
    public function it_updates_role()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'role_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $role = Role::create(['name' => 'update_role']);
        $permission = Permission::create(['name' => 'update_permission']);
        $role->permissions()->sync($permission->id);


        $newPermission = Permission::create(['name' => 'new_update_permission']);

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->put(route('admin.roles.update', $role), [
            'title' => 'updated_role',
            'permissions' => [$newPermission->id]
        ]);

        $response->assertRedirect(route('admin.roles.index'));
        $this->assertDatabaseHas('roles', ['title' => 'updated_role']);
        $this->assertDatabaseHas('permission_role', [
            'role_id' => $role->id,
            'permission_id' => $newPermission->id,
        ]);
    }

    /** @test */
    public function it_displays_role()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'role_show']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $role = Role::create(['title' => 'show_role']);
        $permission = Permission::create(['title' => 'show_permission']);
        $role->permissions()->sync($permission->id);

        $response = $this->get(route('admin.roles.show', $role));

        $response->assertStatus(200);
        $response->assertViewIs('admin.roles.show');
        $response->assertViewHas('role', $role);
    }

    /** @test */
    public function it_deletes_role()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'role_delete']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $role = Role::create(['title' => 'delete_role']);

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->delete(route('admin.roles.destroy', $role));

        $this->assertSoftDeleted('roles', ['title' => 'delete_role']);
    }

    /** @test */
    public function it_mass_deletes_roles()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'role_delete']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $roles = Role::factory()->count(3)->create();
        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->delete(route('admin.roles.massDestroy'), [
            'ids' => $roles->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        foreach ($roles as $role) {
            $this->assertSoftDeleted('roles', ['id' => $role->id]);
        }
    }
}
