<?php

namespace Database\Factories;

use App\Models\Drug;
use Illuminate\Database\Eloquent\Factories\Factory;

class DrugFactory extends Factory
{
    protected $model = Drug::class;

    public function definition()
    {
        return [
            'rxcui' => $this->faker->randomNumber(),
            'name' => $this->faker->word,
            'synonym' => $this->faker->word,
            'language' => $this->faker->languageCode,
            'psn' => $this->faker->word,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
