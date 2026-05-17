<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotPortalRecurringService;

class ChatbotPortalRecurringServiceFactory extends Factory
{
    protected $model = ChatbotPortalRecurringService::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'customer_id' => fake()->numberBetween(1, 1000),
            'job_id' => fake()->numberBetween(1, 5000),
            'frequency' => fake()->randomElement(['weekly', 'fortnightly', 'monthly', 'custom']),
            'is_paused' => fake()->boolean(10),
            'pause_until' => fake()->optional()->date(),
            'skip_next' => fake()->boolean(5),
            'permanent_extras' => ['windows' => fake()->boolean(), 'fridge' => fake()->boolean()],
            'notes' => fake()->optional()->sentence(),
        ];
    }
}

