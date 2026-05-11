<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MarketingCampaign>
 */
class MarketingCampaignFactory extends Factory
{
    public function definition(): array
    {
        $startsAt = fake()->dateTimeBetween('-2 months', '+2 months');

        return [
            'organization_id' => Organization::factory(),
            'name' => fake()->sentence(3),
            'channel' => fake()->randomElement(['email', 'sms', 'social', 'push']),
            'status' => fake()->randomElement(['draft', 'scheduled', 'running', 'completed']),
            'starts_at' => $startsAt,
            'ends_at' => fake()->dateTimeBetween($startsAt, '+3 months'),
            'description' => fake()->optional(0.6)->paragraph(),
        ];
    }
}
