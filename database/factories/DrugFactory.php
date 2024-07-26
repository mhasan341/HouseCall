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
            'rxcui' => fake()->uuid(),
            'name' => fake()->name(),
            'synonym' => fake()->text(),
            'language' => fake()->languageCode(),
            'psn' => fake()->text(),
        ];
    }
}
