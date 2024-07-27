<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Drug;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

class DrugsApiControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_unauthenticated()
    {
        $response = $this->getJson(route('api.drugs.index'));
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_index()
    {
        $this->actingAs(User::factory()->create());
        Drug::factory()->create();

        $response = $this->getJson(route('api.drugs.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_store()
    {
        $this->actingAs(User::factory()->create());
        $drugData = Drug::factory()->make()->toArray();

        $response = $this->postJson(route('api.drugs.store'), $drugData);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJsonFragment(['name' => $drugData['name']]);
    }

    public function test_show_unauthenticated()
    {
        $drug = Drug::factory()->create();

        $response = $this->getJson(route('api.drugs.show', $drug->id));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_show()
    {
        $this->actingAs(User::factory()->create());
        $drug = Drug::factory()->create();

        $response = $this->getJson(route('api.drugs.show', $drug->id));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonFragment(['name' => $drug->name]);
    }

    public function test_update()
    {
        $this->actingAs(User::factory()->create());
        $drug = Drug::factory()->create();
        $updatedData = ['name' => 'Updated Name', 'rxcui'=>'abc'];

        $response = $this->putJson(route('api.drugs.update', $drug->id), $updatedData);

        $response->assertStatus(Response::HTTP_ACCEPTED);
        $response->assertJsonFragment(['name' => 'Updated Name', 'rxcui'=>'abc']);
    }

    public function test_destroy_unauthenticated()
    {
        $drug = Drug::factory()->create();

        $response = $this->deleteJson(route('api.drugs.destroy', $drug->id));

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_destroy()
    {
        $this->actingAs(User::factory()->create());
        $drug = Drug::factory()->create();

        $response = $this->deleteJson(route('api.drugs.destroy', $drug->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('drugs', ['id' => $drug->id]);
    }

    public function test_search()
    {
        $response = $this->getJson(route('drugs.search', ['drug_name' => 'cymbalta']));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'rxcui',
                    'name'
                ]
            ]
        ]);
    }

    public function test_search_failed()
    {
        $response = $this->getJson(route('drugs.search', ['drug_name' => 'InvalidDrugName']));

        $response->assertStatus(500);
        $response->assertJson([
            'status' => false,
            'message' => 'Failed to fetch medications'
        ]);
    }

    public function test_save_user_medication()
    {
        // Create a user and a drug
        $user = User::factory()->create();
        $this->actingAs($user);
        $drug = Drug::factory()->create(['rxcui' => '123456']);

        // Authenticate as the created user
        $this->actingAs($user);

        // Send the request to save user medication
        $response = $this->postJson(route('api.medication.save'), ['rxcui' => '123456']);

        // Assert the response status
        $response->assertStatus(201);
        $response->assertJson([
            'status' => true,
            'message' => 'Medication saved successfully'
        ]);

        // Assert the drug_user table has the correct entry
        $this->assertDatabaseHas('drug_user', [
            'user_id' => $user->id,
            'drug_id' => $drug->id
        ]);
    }

    public function test_delete_user_medication()
    {
        // Create a user and a drug
        $user = User::factory()->create();
        $drug = Drug::factory()->create(['rxcui' => '123456']);

        // Attach the drug to the user
        $user->drugs()->attach($drug->id);

        // Authenticate as the created user
        $this->actingAs($user);

        // Send the request to delete user medication
        $response = $this->deleteJson(route('api.medication.delete'), ['rxcui' => '123456']);

        // Assert the response status
        $response->assertStatus(201);
        $response->assertJson([
            'status' => true,
            'message' => 'Medication removed successfully'
        ]);

        // Assert the drug_user table does not have the entry
        $this->assertDatabaseMissing('drug_user', [
            'user_id' => $user->id,
            'drug_id' => $drug->id
        ]);
    }


    public function test_get_user_medication()
    {
        // Create a user and a drug
        $user = User::factory()->create();
        $drug = Drug::factory()->create(['rxcui' => '123456', 'name' => 'Aspirin']);

        // Attach the drug to the user
        $user->drugs()->attach($drug->id);

        // Authenticate as the created user
        $this->actingAs($user);

        // Send the request to get user medications
        $response = $this->getJson(route('api.medication.all'));

        // Assert the response status
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'data' => [
                [
                    'rxcui' => '123456',
                    'name' => 'Aspirin'
                ]
            ]
        ]);
    }

    public function test_get_medication_details()
    {
        // Create a user and a drug
        $user = User::factory()->create();
        $drug = Drug::factory()->create(['rxcui' => '123456', 'name' => 'Aspirin']);

        // Authenticate as the created user
        $this->actingAs($user);

        // Send the request to get medication details
        $response = $this->getJson(route('api.medication.details', ['rxcui' => '123456']));

        // Assert the response status
        $response->assertStatus(201);
        $response->assertJson([
            'status' => true,
            'message' => 'Medication fetched successfully',
            'data' => [
                'rxcui' => '123456',
                'name' => 'Aspirin'
            ]
        ]);
    }

}
