<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotCannedResponse;

class ChatbotCannedResponseFactory extends Factory
{
    protected $model = ChatbotCannedResponse::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'title' => fake()->sentence(4),
            'content' => fake()->paragraph(),
            'is_portal_friendly' => fake()->boolean(90),
            'sort_order' => fake()->numberBetween(0, 100),
        ];
    }
}

