<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System\Services\VoiceAI;

use Illuminate\Support\Facades\Http;

class BlandAiClient
{
    public function __construct(
        private readonly string $apiKey
    ) {}

    public function createCall(array $payload): array
    {
        // Payload example: ['phone_number'=>..., 'task'=>..., 'voice'=>..., ...]
        $resp = Http::withHeaders([
            'Authorization' => $this->apiKey,
            'Content-Type'  => 'application/json',
        ])->post('https://api.bland.ai/v1/calls', $payload);

        return $resp->json() ?? [];
    }
}
