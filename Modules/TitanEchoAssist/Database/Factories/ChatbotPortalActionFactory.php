<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotPortalAction;

class ChatbotPortalActionFactory extends Factory
{
    protected $model = ChatbotPortalAction::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'customer_id' => fake()->numberBetween(1, 1000),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'session_id' => fake()->uuid(),
            'action_type' => fake()->randomElement(['book_visit', 'approve_quote', 'confirm_service_job', 'pay_invoice', 'request_reclean']),
            'payload' => ['source' => 'portal', 'request_id' => fake()->uuid()],
            'status' => fake()->randomElement(['queued', 'processing', 'completed', 'failed', 'cancelled']),
            'result' => fake()->optional()->randomElement([['message' => 'ok'], ['message' => 'retry']]),
            'processed_at' => fake()->optional()->dateTimeBetween('-2 days', 'now'),
        ];
    }
}

