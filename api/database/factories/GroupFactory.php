<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    public function definition(): array
    {
        return [
            'creator_id' => User::factory(),
            'name' => fake()->sentence(),
            'description' => fake()->sentence(),
        ];
    }
}
