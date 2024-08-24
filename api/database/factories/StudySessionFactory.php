<?php

namespace Database\Factories;

use App\Models\Group;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudySessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'group_id' => Group::factory(),
            'title' => fake()->sentence(),
            'description' => fake()->sentence(),
            'scheduled_at' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d H:i:s'),
        ];
    }
}
