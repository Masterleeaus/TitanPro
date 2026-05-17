<?php

namespace App\Services\TitanNexus\VoiceAi;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class BlandAiClient
{
    public function request(): PendingRequest
    {
        return Http::baseUrl(config('titannexus_voice_ai.bland.base_url'))
            ->acceptJson()
            ->asJson()
            ->withHeaders(array_filter([
                'Authorization' => config('titannexus_voice_ai.bland.api_key'),
            ]))
            ->timeout(30);
    }

    public function makeCall(string $phoneNumber, string $task, array $parameters = []): array
    {
        $payload = array_merge([
            'phone_number' => $phoneNumber,
            'task' => $task,
        ], $parameters);

        $response = $this->request()
            ->withHeaders(array_filter([
                'encrypted_key' => config('titannexus_voice_ai.bland.encrypted_key'),
            ]))
            ->post('/v1/calls', $payload);

        return $response->json() ?? [
            'error' => $response->body(),
            'status' => $response->status(),
        ];
    }

    public function calls(array $filters = []): array
    {
        return $this->request()->get('/v1/calls', $filters)->json() ?? [];
    }

    public function call(string $callId): array
    {
        return $this->request()->get('/v1/calls/' . $callId)->json() ?? [];
    }

    public function recording(string $callId): array
    {
        return $this->request()->get('/v1/calls/' . $callId . '/recording')->json() ?? [];
    }

    public function correctedTranscript(string $callId): array
    {
        return $this->request()->get('/v1/calls/corrected-transcript/' . $callId)->json() ?? [];
    }

    public function voices(): array
    {
        return $this->request()->get('/v1/voices')->json() ?? [];
    }

    public function knowledgebase(): array
    {
        return $this->request()->get('/v1/knowledgebases')->json() ?? [];
    }

    public function tools(): array
    {
        return $this->request()->get('/v1/tools')->json() ?? [];
    }
}
