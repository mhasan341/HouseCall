<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;
use App\Models\Drug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

class DrugsControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_retrieve_all_drugs()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'drug_access']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Create drugs
        Drug::factory()->count(3)->create();

        // Send the request to retrieve all drugs
        $response = $this->get(route('admin.drugs.index'));

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertViewIs('admin.drugs.index');
    }

    /** @test */
    public function can_store_drug()
    {
        // Create a user with the necessary permission
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'drug_create']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Prepare drug data
        $drugData = Drug::factory()->make()->toArray();

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Send the request to store a new drug
        $response = $this->post(route('admin.drugs.store'), $drugData);

        // Assert the response status and structure
        $response->assertStatus(Response::HTTP_FOUND); // Assuming redirect after store

        // Assert the drug is stored correctly
        $this->assertDatabaseHas('drugs', [
            'name' => $drugData['name']
        ]);
    }

    /** @test */
    public function can_show_drug()
    {
        // Create a user with the necessary permission
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'drug_show']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Create a drug
        $drug = Drug::factory()->create();

        // Send the request to show the drug
        $response = $this->get(route('admin.drugs.show', $drug->id));

        // Assert the response status and structure
        $response->assertStatus(200);
        $response->assertViewHas('drug', $drug);
    }

    /** @test */
    public function can_update_drug()
    {
        // Create a user with the necessary permission
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'drug_edit']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Create a drug
        $drug = Drug::factory()->create();

        // Prepare updated drug data
        $updatedData = [
            'rxcui' => 'ABC',
            'name' => 'Updated Name',
        ];

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);


        // Send the request to update the drug
        $response = $this->put(route('admin.drugs.update', $drug), $updatedData);

        // Assert the response status and structure
        $response->assertStatus(Response::HTTP_FOUND); // Assuming redirect after update

        // Assert the drug is updated correctly
        $this->assertDatabaseHas('drugs', [
            'id' => $drug->id,
            'name' => 'Updated Name'
        ]);
    }

    /** @test */
    public function can_delete_drug()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'drug_delete']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Create a drug
        $drug = Drug::factory()->create();

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Send the request to delete the drug
        $response = $this->delete(route('admin.drugs.destroy', $drug));

        // Assert the response status
        $response->assertStatus(Response::HTTP_FOUND); // Assuming redirect after delete

        // Assert the drug is soft deleted
        $this->assertDatabaseMissing('drugs', ['id' => $drug->id]);
    }

    /** @test */
    public function can_mass_destroy_drugs()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'drug_delete']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Create drugs
        $drugs = Drug::factory()->count(3)->create();

        // Prepare the request data
        $requestData = ['ids' => $drugs->pluck('id')->toArray()];

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Send the request to mass destroy the drugs
        $response = $this->delete(route('admin.drugs.massDestroy'), $requestData);

        // Assert the response status
        $response->assertStatus(Response::HTTP_NO_CONTENT);

        // Assert the drugs are soft deleted
        foreach ($drugs as $drug) {
            $this->assertDatabaseMissing('drugs', ['id' => $drug->id]);
        }
    }

    /** @test */
    public function can_create_drug()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'drug_access']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Prepare drug data
        $drugData = Drug::factory()->make()->toArray();

        // Send the request to store a new drug
        $response = $this->get(route('admin.drugs.create'), $drugData);

        $response->assertStatus(Response::HTTP_OK);
    }

    /** @test */
    public function can_edit_drug()
    {
        $adminRole = Role::create(['title' => 'Admin']);
        $permission = Permission::create(['title' => 'drug_access']);
        $adminRole->permissions()->attach($permission->id);
        $adminUser = User::factory()->create();
        $adminUser->roles()->attach($adminRole->id);
        // Authenticate as the Admin user
        $this->actingAs($adminUser);

        // Create a drug
        $drug = Drug::factory()->create();

        // Prepare updated drug data
        $updatedData = [
            'rxcui' => 'ABC',
            'name' => 'Updated Name'
        ];

        // Disable CSRF protection for this test
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // Send the request to update the drug
        $response = $this->get(route('admin.drugs.edit', $drug->id), $updatedData);

        $response->assertStatus(Response::HTTP_OK);
    }


}

