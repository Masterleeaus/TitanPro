<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotPortalBookingRequest;

class ChatbotPortalBookingRequestFactory extends Factory
{
    protected $model = ChatbotPortalBookingRequest::class;

    public function definition(): array
    {
        return [
            'company_id' => fake()->numberBetween(1, 100),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'customer_id' => fake()->numberBetween(1, 1000),
            'session_id' => fake()->uuid(),
            'requested_at' => fake()->dateTimeBetween('-2 days', 'now'),
            'preferred_date' => fake()->dateTimeBetween('now', '+14 days')->format('Y-m-d'),
            'preferred_time' => fake()->time('H:i:s'),
            'service_type' => fake()->randomElement(['deep_clean', 'regular_clean', 'inspection']),
            'notes' => fake()->sentence(),
            'status' => fake()->randomElement(['pending', 'confirmed', 'declined', 'cancelled']),
            'confirmed_job_id' => fake()->optional()->numberBetween(1, 5000),
        ];
    }
}

