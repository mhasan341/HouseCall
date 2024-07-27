<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

class PermissionsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_permissions_index()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'permission_access']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);
        $this->actingAs($adminUser);

        $response = $this->get(route('admin.permissions.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.permissions.index');
        $response->assertViewHas('permissions');
    }

    /** @test */
    public function it_displays_permission_creation_form()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'permission_create']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $response = $this->get(route('admin.permissions.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.permissions.create');
    }

    /** @test */
    public function it_stores_permission()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'permission_create']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->post(route('admin.permissions.store'), [
            'title' => 'new_permission'
        ]);

        $response->assertRedirect(route('admin.permissions.index'));
        $this->assertDatabaseHas('permissions', ['title' => 'new_permission']);
    }

    /** @test */
    public function it_displays_permission_edit_form()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'permission_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $permission = Permission::create(['title' => 'edit_permission']);

        $response = $this->get(route('admin.permissions.edit', $permission));

        $response->assertStatus(200);
        $response->assertViewIs('admin.permissions.edit');
        $response->assertViewHas('permission', $permission);
    }

    /** @test */
    public function it_updates_permission()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'permission_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $permission = Permission::create(['title' => 'update_permission']);

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->put(route('admin.permissions.update', $permission), [
            'title' => 'updated_permission'
        ]);

        $response->assertRedirect(route('admin.permissions.index'));
        $this->assertDatabaseHas('permissions', ['title' => 'updated_permission']);
    }

    /** @test */
    public function it_displays_permission_for_admin()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'permission_show']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $permission = Permission::create(['name' => 'show_permission']);

        $response = $this->get(route('admin.permissions.show', $permission));

        $response->assertStatus(200);
        $response->assertViewIs('admin.permissions.show');
        $response->assertViewHas('permission', $permission);
    }

    /** @test */
    public function it_deletes_permission_for_admin()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'permission_delete']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $permission = Permission::create(['title' => 'test_permission']);

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->delete(route('admin.permissions.destroy', $permission));

        $response->assertRedirect();
        $this->assertSoftDeleted('permissions', ['title' => 'test_permission']);
    }

    /** @test */
    public function it_mass_deletes_permissions_for_admin()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'permission_delete']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $permissions = Permission::factory()->count(3)->create();
        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        $response = $this->delete(route('admin.permissions.massDestroy'), [
            'ids' => $permissions->pluck('id')->toArray()
        ]);

        $response->assertStatus(Response::HTTP_NO_CONTENT);

        foreach ($permissions as $permission) {
            $this->assertSoftDeleted('permissions', ['id' => $permission->id]);
        }
    }
}
