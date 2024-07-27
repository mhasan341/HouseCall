<?php

namespace Tests\Unit;

use App\Http\Resources\Admin\DrugResource;
use App\Models\Drug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DrugResourceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the DrugResource transforms the model correctly.
     *
     * @return void
     */
    public function testDrugResource()
    {
        // Create a test drug using the factory
        $drug = Drug::factory()->create([
            'name' => 'Aspirin',
            'synonym' => 'Acetylsalicylic Acid',
            'rxcui' => '123',
            'language' => 'ENG',
            'psn' => 'abc'
        ]);

        // Create the resource
        $resource = new DrugResource($drug);

        // Transform the resource into an array
        $resourceArray = $resource->toArray(request());

        // Extract only name and synonym fields from the resource array
        $filteredArray = collect($resourceArray)->only(['name', 'synonym'])->all();

        // Assert the transformed array matches the expected output for name and synonym
        $this->assertEquals([
            'name' => 'Aspirin',
            'synonym' => 'Acetylsalicylic Acid'
        ], $filteredArray);
    }
}
