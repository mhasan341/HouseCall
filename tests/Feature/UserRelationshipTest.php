<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_have_roles()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create();

        $user->roles()->attach($role);

        $this->assertTrue($user->roles->contains($role));
    }
}
