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
}
