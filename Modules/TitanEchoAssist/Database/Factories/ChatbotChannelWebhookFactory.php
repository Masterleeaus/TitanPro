<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotChannelWebhook;

class ChatbotChannelWebhookFactory extends Factory
{
    protected $model = ChatbotChannelWebhook::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'channel_id' => fake()->optional()->numberBetween(1, 500),
            'provider' => fake()->randomElement(['whatsapp', 'telegram', 'messenger', 'instagram', 'sms']),
            'webhook_url' => fake()->url(),
            'verify_token' => fake()->sha1(),
            'secret' => fake()->sha1(),
            'is_active' => true,
            'last_received_at' => fake()->optional()->dateTimeBetween('-7 days', 'now'),
            'payload' => ['event' => 'message.received'],
        ];
    }
}

