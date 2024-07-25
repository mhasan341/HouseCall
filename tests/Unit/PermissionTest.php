<?php

namespace Tests\Unit;

use App\Models\Role;
use Carbon\Carbon;
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

    /** @test */
    public function it_serializes_dates_in_custom_format()
    {
        $drug = Permission::factory()->create([
            'created_at' => Carbon::parse('2023-01-01 12:00:00'),
            'updated_at' => Carbon::parse('2023-01-01 13:00:00'),
        ]);

        $expectedJson = [
            'created_at' => '2023-01-01 12:00:00',
            'updated_at' => '2023-01-01 13:00:00',
        ];

        $this->assertEquals($expectedJson['created_at'], $drug->toArray()['created_at']);
        $this->assertEquals($expectedJson['updated_at'], $drug->toArray()['updated_at']);
    }
}
