<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LeadPipelineEntry>
 */
class LeadPipelineEntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'source' => fake()->randomElement(['website', 'referral', 'social', 'ads']),
            'stage' => fake()->randomElement(['new', 'qualified', 'proposal', 'won', 'lost']),
            'notes' => fake()->optional(0.5)->sentence(),
        ];
    }
}
