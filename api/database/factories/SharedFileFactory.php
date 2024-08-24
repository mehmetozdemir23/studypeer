<?php

namespace Database\Factories;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SharedFile>
 */
class SharedFileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'group_id' => Group::factory(),
            'uploader_id' => User::factory(),
            'name' => fake()->sentence(),
            'description' => fake()->text(),
            'file_path' => fake()->filePath(),
        ];
    }
}
