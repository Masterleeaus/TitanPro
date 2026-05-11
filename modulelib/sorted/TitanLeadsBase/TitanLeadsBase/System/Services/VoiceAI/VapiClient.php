<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System\Services\VoiceAI;

use Illuminate\Support\Facades\Http;

class VapiClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = 'https://api.vapi.ai'
    ) {}

    public function startCall(string $to, string $assistantId, array $meta = []): array
    {
        // Scaffold: wire to actual Vapi endpoint in later pass.
        $resp = Http::withToken($this->apiKey)
            ->post($this->baseUrl . '/call', [
                'phoneNumber' => $to,
                'assistantId' => $assistantId,
                'metadata' => $meta,
            ]);

        return $resp->json() ?? [];
    }
}
