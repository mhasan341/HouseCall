<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Drug;
use App\Models\User;
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
            'synonym' => 'TD',
            'language' => 'en',
            'psn' => 'test-psn',
        ]);

        $this->assertDatabaseHas('drugs', [
            'rxcui' => 12345,
            'name' => 'Test Drug',
            'synonym' => 'TD',
            'language' => 'en',
            'psn' => 'test-psn',
        ]);
    }

    /** @test */
    public function it_can_update_a_drug()
    {
        $drug = Drug::factory()->create();

        $drug->update([
            'name' => 'Updated Drug',
            'synonym' => 'UD',
            'language' => 'fr',
            'psn' => 'updated-psn',
        ]);

        $this->assertDatabaseHas('drugs', [
            'id' => $drug->id,
            'name' => 'Updated Drug',
            'synonym' => 'UD',
            'language' => 'fr',
            'psn' => 'updated-psn',
        ]);
    }

    /** @test */
    public function it_can_delete_a_drug()
    {
        $drug = Drug::factory()->create();

        $drug->delete();

        $this->assertDatabaseMissing('drugs', [
            'id' => $drug->id,
        ]);
    }

    /** @test */
    public function a_drug_can_have_multiple_users()
    {
        $drug = Drug::factory()->create();
        $users = User::factory()->count(3)->create();

        $drug->users()->attach($users);

        $this->assertCount(3, $drug->users);
    }

    /** @test */
    public function a_user_can_be_attached_to_a_drug()
    {
        $drug = Drug::factory()->create();
        $user = User::factory()->create();

        $drug->users()->attach($user);

        $this->assertTrue($drug->users->contains($user));
    }

    /** @test */
    public function a_user_can_be_detached_from_a_drug()
    {
        $drug = Drug::factory()->create();
        $user = User::factory()->create();

        $drug->users()->attach($user);
        $drug->users()->detach($user);

        $this->assertFalse($drug->users->contains($user));
    }
}
