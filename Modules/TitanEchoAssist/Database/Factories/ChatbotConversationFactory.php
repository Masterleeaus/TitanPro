<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\Conversation;

class ChatbotConversationFactory extends Factory
{
    protected $model = Conversation::class;

    public function definition(): array
    {
        return [
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'session_id' => fake()->uuid(),
            'conversation_name' => fake()->name(),
            'email' => fake()->optional()->safeEmail(),
            'rating' => fake()->optional()->numberBetween(1, 5),
            'reviewed_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'last_activity_at' => fake()->dateTimeBetween('-1 day', 'now'),
            'internal_notes' => fake()->optional()->sentence(),
            'is_pinned' => fake()->boolean(10),
            'source_channel' => fake()->randomElement(['webchat', 'whatsapp', 'telegram', 'voice']),
            'voice_call_duration' => fake()->optional()->numberBetween(10, 1800),
            'voice_recording_url' => fake()->optional()->url(),
            'company_id' => fake()->numberBetween(1, 100),
        ];
    }
}

