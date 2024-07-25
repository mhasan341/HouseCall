<?php

namespace Tests\Unit;

use App\Models\Drug;
use Carbon\Carbon;
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

    /** @test */
    public function it_serializes_dates_in_custom_format()
    {
        $drug = Role::factory()->create([
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
