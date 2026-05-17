<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotPortalSiteProfile;

class ChatbotPortalSiteProfileFactory extends Factory
{
    protected $model = ChatbotPortalSiteProfile::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'customer_id' => fake()->numberBetween(1, 1000),
            'property_id' => fake()->numberBetween(1, 5000),
            'alarm_code' => fake()->numerify('####'),
            'alarm_instructions' => fake()->optional()->sentence(),
            'pets' => [
                ['name' => fake()->firstName(), 'type' => 'dog', 'instructions' => fake()->sentence()],
            ],
            'parking' => fake()->optional()->sentence(3),
            'access_method' => fake()->randomElement(['key', 'lockbox', 'gate_code', 'doorbell', 'open']),
            'priority_rooms' => fake()->randomElements(['kitchen', 'bathroom', 'living_room', 'hallway'], 2),
            'special_instructions' => fake()->optional()->paragraph(),
            'last_updated_at' => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}

