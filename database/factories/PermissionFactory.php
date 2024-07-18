<?php

namespace Database\Factories;

use App\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
