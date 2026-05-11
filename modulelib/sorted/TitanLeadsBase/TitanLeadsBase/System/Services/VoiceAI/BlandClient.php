<?php

declare(strict_types=1);

namespace App\Extensions\TitanLeads\System\Services\VoiceAI;

use Illuminate\Support\Facades\Http;

class BlandClient
{
    public function __construct(
        private readonly string $apiKey,
        private readonly string $baseUrl = 'https://api.bland.ai'
    ) {}

    public function startCall(string $to, string $from, string $script, array $meta = []): array
    {
        // Scaffold: wire to actual Bland AI endpoint in later pass.
        $resp = Http::withToken($this->apiKey)
            ->post($this->baseUrl . '/v1/calls', [
                'to' => $to,
                'from' => $from,
                'task' => $script,
                'metadata' => $meta,
            ]);

        return $resp->json() ?? [];
    }
}
