<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class ChangePasswordControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_password_edit()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'profile_password_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        $response = $this->get(route('profile.password.edit'));

        $response->assertStatus(200);
        $response->assertViewIs('auth.passwords.edit');
    }

    /** @test */
    public function it_updates_password()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'profile_password_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $response = $this->post(route('profile.password.update'), [
            'password' => 'newpassword',
            'password_confirmation' => 'newpassword',
        ]);

        $response->assertRedirect(route('profile.password.edit'));
        $response->assertSessionHas('message', __('global.change_password_success'));

        $this->assertTrue(Hash::check('newpassword', $adminUser->fresh()->password));
    }

    /** @test */
    public function it_updates_profile()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'profile_password_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $response = $this->post(route('profile.password.updateProfile'), [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $response->assertRedirect(route('profile.password.edit'));
        $response->assertSessionHas('message', __('global.update_profile_success'));

        $adminUser = $adminUser->fresh();
        $this->assertEquals('Updated Name', $adminUser->name);
        $this->assertEquals('updated@example.com', $adminUser->email);
    }

    /** @test */
    public function it_deletes_account()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'profile_password_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $response = $this->post(route('profile.password.destroyProfile'));

        $response->assertSessionHas('message', __('global.delete_account_success'));

        $this->assertSoftDeleted('users', ['id' => $adminUser->id]);
    }
}
