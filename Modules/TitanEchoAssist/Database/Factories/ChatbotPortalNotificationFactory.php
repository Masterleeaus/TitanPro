<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotPortalNotification;

class ChatbotPortalNotificationFactory extends Factory
{
    protected $model = ChatbotPortalNotification::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'customer_id' => fake()->numberBetween(1, 1000),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'event_type' => fake()->slug(2),
            'title' => fake()->sentence(4),
            'body' => fake()->sentence(8),
            'action_url' => fake()->optional()->url(),
            'is_read' => fake()->boolean(30),
            'read_at' => fake()->optional()->dateTimeBetween('-7 days', 'now'),
            'sent_at' => fake()->dateTimeBetween('-7 days', 'now'),
            'channel' => fake()->randomElement(['in_app', 'push', 'sms', 'email']),
        ];
    }
}

