<?php

namespace Tests\Unit;

use App\Models\Drug;
use App\Models\Role;
use Carbon\Carbon;
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

    /** @test */
    public function it_can_check_if_user_has_a_role()
    {
        $user = User::factory()->create();
        $role = Role::factory()->create(['title' => 'Admin']);

        $user->roles()->attach($role);

        $this->assertTrue($user->hasRole('Admin'));
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

    /** @test */
    public function a_user_can_have_drugs()
    {
        $user = User::factory()->create();
        $drugs = Drug::factory()->count(3)->create();

        $user->drugs()->attach($drugs);

        $this->assertCount(3, $user->drugs);
        foreach ($drugs as $drug) {
            $this->assertTrue($user->drugs->contains($drug));
        }
    }

    /** @test */
    public function it_can_check_if_user_is_admin()
    {
        $user = User::factory()->create();
        $adminRole = Role::factory()->create(['title' => 'Admin']);
        $userRole = Role::factory()->create(['title' => 'User']);

        $user->roles()->attach($adminRole);

        $this->assertTrue($user->is_admin);

        $user->roles()->detach($adminRole);
        $user->roles()->attach($userRole);

        $this->assertFalse($user->is_admin);
    }
}
