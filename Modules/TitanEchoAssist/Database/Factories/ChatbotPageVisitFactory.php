<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotPageVisit;

class ChatbotPageVisitFactory extends Factory
{
    protected $model = ChatbotPageVisit::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'session_id' => fake()->uuid(),
            'visitor_id' => fake()->optional()->numberBetween(1, 1000),
            'page_url' => fake()->url(),
            'page_title' => fake()->sentence(4),
            'referrer' => fake()->optional()->url(),
            'duration_seconds' => fake()->optional()->numberBetween(5, 600),
            'visited_at' => fake()->dateTimeBetween('-1 day', 'now'),
        ];
    }
}

