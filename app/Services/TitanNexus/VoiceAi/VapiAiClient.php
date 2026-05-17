<?php

namespace App\Services\TitanNexus\VoiceAi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class VapiAiClient
{
    public function request(): PendingRequest
    {
        return Http::baseUrl(config('titannexus_voice_ai.vapi.base_url'))
            ->acceptJson()
            ->asJson()
            ->withToken((string) config('titannexus_voice_ai.vapi.api_key'))
            ->timeout(30);
    }

    public function createCall(array $payload): array
    {
        $response = $this->request()->post('/call', $payload);

        return $response->json() ?? [
            'error' => $response->body(),
            'status' => $response->status(),
        ];
    }

    public function calls(array $filters = []): array
    {
        return $this->request()->get('/call', $filters)->json() ?? [];
    }

    public function call(string $callId): array
    {
        return $this->request()->get('/call/' . $callId)->json() ?? [];
    }

    public function assistants(): array
    {
        return $this->request()->get('/assistant')->json() ?? [];
    }

    public function tools(): array
    {
        return $this->request()->get('/tool')->json() ?? [];
    }

    public function phoneNumbers(): array
    {
        return $this->request()->get('/phone-number')->json() ?? [];
    }

    public function voices(): array
    {
        return $this->request()->get('/voice')->json() ?? [];
    }

    public function files(): array
    {
        return $this->request()->get('/file')->json() ?? [];
    }

    public function knowledgebase(): array
    {
        return $this->request()->get('/knowledge-base')->json() ?? [];
    }
}
