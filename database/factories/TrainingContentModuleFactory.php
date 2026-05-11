<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TrainingContentModule>
 */
class TrainingContentModuleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'title' => fake()->sentence(3),
            'audience' => fake()->randomElement(['owner', 'admin', 'team']),
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'duration_minutes' => fake()->numberBetween(15, 180),
            'description' => fake()->optional(0.6)->paragraph(),
        ];
    }
}
