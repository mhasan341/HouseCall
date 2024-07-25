<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_permission()
    {
        $permission = Permission::factory()->create([
            'title' => 'edit-posts',
        ]);

        $this->assertDatabaseHas('permissions', [
            'title' => 'edit-posts',
        ]);
    }
}
