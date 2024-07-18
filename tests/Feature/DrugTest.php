<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Drug;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DrugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_drug()
    {
        $drug = Drug::factory()->create([
            'rxcui' => 12345,
            'name' => 'Test Drug',
            'description' => 'This is a test drug.',
            'side_effects' => 'None',
        ]);

        $this->assertDatabaseHas('drugs', [
            'rxcui' => 12345,
            'name' => 'Test Drug',
        ]);
    }
}
