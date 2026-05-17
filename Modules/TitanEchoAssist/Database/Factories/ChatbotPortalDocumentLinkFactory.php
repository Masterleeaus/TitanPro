<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotPortalDocumentLink;

class ChatbotPortalDocumentLinkFactory extends Factory
{
    protected $model = ChatbotPortalDocumentLink::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'customer_id' => fake()->numberBetween(1, 1000),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'document_type' => fake()->randomElement(['invoice', 'contract', 'receipt', 'quote', 'report', 'other']),
            'title' => fake()->sentence(4),
            'file_path' => 'documents/'.fake()->uuid().'.pdf',
            'external_url' => fake()->optional()->url(),
            'expires_at' => fake()->optional()->dateTimeBetween('now', '+30 days'),
            'is_signed' => fake()->boolean(40),
            'signed_at' => fake()->optional()->dateTimeBetween('-14 days', 'now'),
        ];
    }
}

