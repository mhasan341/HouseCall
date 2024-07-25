<?php

namespace Tests\Unit;

use App\Models\Role;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_user()
    {
        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    public function a_user_can_have_roles()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $user->roles()->attach($role);

        $this->assertTrue($user->roles->contains($role));
    }
}
