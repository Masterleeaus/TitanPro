<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotPortalAutomationLog;

class ChatbotPortalAutomationLogFactory extends Factory
{
    protected $model = ChatbotPortalAutomationLog::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'trigger_event' => fake()->slug(3),
            'trigger_payload' => ['session_id' => fake()->uuid(), 'channel' => 'portal'],
            'action_taken' => fake()->randomElement(['queue_booking', 'notify_operator', 'create_task']),
            'action_payload' => ['status' => 'queued'],
            'outcome' => fake()->randomElement(['success', 'failed', 'skipped']),
            'processed_at' => fake()->optional()->dateTimeBetween('-3 days', 'now'),
        ];
    }
}
