<?php

namespace Modules\TitanEchoAssist\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\TitanEchoAssist\Models\ChatbotAvatar;

class ChatbotAvatarFactory extends Factory
{
    protected $model = ChatbotAvatar::class;

    public function definition(): array
    {
        $fileName = fake()->uuid().'.png';

        return [
            'company_id' => fake()->numberBetween(1, 100),
            'chatbot_id' => fake()->numberBetween(1, 1000),
            'file_path' => 'avatars/'.$fileName,
            'file_name' => $fileName,
            'mime_type' => 'image/png',
            'size' => fake()->numberBetween(5_000, 250_000),
            'is_default' => false,
            'avatar' => 'uploads/avatars/'.$fileName,
            'user_id' => fake()->numberBetween(1, 1000),
        ];
    }
}

