<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_role()
    {
        $role = Role::factory()->create([
            'title' => 'admin',
        ]);

        $this->assertDatabaseHas('roles', [
            'title' => 'admin',
        ]);
    }
}
