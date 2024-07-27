<?php

namespace Tests\Feature;

use App\Models\Drug;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function admin_user_can_see_total_users_and_total_drugs()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Get users and drugs
        $totalUser = User::get();
        $totalDrug = Drug::get();


        // Send the request to the home page
        $response = $this->get(route('admin.home'));

        // Assert the response status and view
        $response->assertStatus(200);
        $response->assertViewIs('home');

        // Assert the view has the correct data
        $response->assertViewHas('totalUsers', $totalUser->count());
        $response->assertViewHas('totalDrugs', $totalDrug->count());
    }
//
//    /** @test */
//    public function regular_user_can_see_only_their_own_total_drugs()
//    {
//        // Create a user and drugs
//        $user = User::factory()->create();
//        Drug::factory()->count(3)->create(['user_id' => $user->id]);
//        Drug::factory()->count(2)->create(); // Drugs for other users
//
//        // Authenticate as the regular user
//        $this->actingAs($user);
//
//        // Send the request to the home page
//        $response = $this->get(route('home'));
//
//        // Assert the response status and view
//        $response->assertStatus(200);
//        $response->assertViewIs('home');
//
//        // Assert the view has the correct data
//        $response->assertViewHas('totalUsers', 0);
//        $response->assertViewHas('totalDrugs', 3);
//    }
}
