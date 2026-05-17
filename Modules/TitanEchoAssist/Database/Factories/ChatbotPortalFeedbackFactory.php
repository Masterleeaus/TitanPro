<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotPortalFeedback;

class ChatbotPortalFeedbackFactory extends Factory
{
    protected $model = ChatbotPortalFeedback::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'customer_id' => fake()->numberBetween(1, 1000),
            'job_id' => fake()->numberBetween(1, 5000),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'session_id' => fake()->uuid(),
            'rating' => fake()->numberBetween(1, 5),
            'comment' => fake()->optional()->sentence(),
            'reclean_requested' => fake()->boolean(20),
            'reclean_reason' => fake()->optional()->sentence(),
            'reclean_scheduled_at' => fake()->optional()->dateTimeBetween('now', '+14 days'),
            'status' => fake()->randomElement(['new', 'reviewed', 'actioned', 'closed']),
        ];
    }
}

