<?php

namespace App\Services\TitanNexus\VoiceAi;

use App\Models\TitanNexus\NexusVoiceCallLog;
use Illuminate\Support\Arr;

class NexusVoiceCallService
{
    public function __construct(
        protected BlandAiClient $bland,
        protected VapiAiClient $vapi,
    ) {}

    public function callLead(array $lead, ?string $provider = null): NexusVoiceCallLog
    {
        $provider = $provider ?: config('titannexus_voice_ai.default_provider', 'bland');
        $phone = (string) Arr::get($lead, 'phone');

        $requestPayload = $this->buildLeadPayload($lead);

        $responsePayload = match ($provider) {
            'vapi' => $this->callLeadWithVapi($phone, $requestPayload),
            default => $this->callLeadWithBland($phone, $requestPayload),
        };

        return NexusVoiceCallLog::create([
            'provider' => $provider,
            'direction' => 'outbound',
            'lead_id' => Arr::get($lead, 'id'),
            'contact_id' => Arr::get($lead, 'contact_id'),
            'phone_number' => $phone,
            'status' => Arr::get($responsePayload, 'status') ?: Arr::get($responsePayload, 'call.status') ?: 'created',
            'call_id' => Arr::get($responsePayload, 'call_id') ?: Arr::get($responsePayload, 'id'),
            'request_payload' => $requestPayload,
            'response_payload' => $responsePayload,
            'started_at' => now(),
        ]);
    }

    protected function callLeadWithBland(string $phone, array $payload): array
    {
        return $this->bland->makeCall($phone, config('titannexus_voice_ai.outbound.system_prompt'), [
            'model' => 'enhanced',
            'max_duration' => config('titannexus_voice_ai.bland.max_duration'),
            'first_sentence' => config('titannexus_voice_ai.outbound.first_sentence'),
            'voice' => config('titannexus_voice_ai.bland.voice'),
            'temperature' => config('titannexus_voice_ai.bland.temperature'),
            'summary_prompt' => 'Summarize the decision maker, service need, objections, timing, and next booking step.',
            'wait_for_greeting' => false,
            'webhook' => url('/api/titan-nexus/voice/webhooks/bland'),
            'webhook_events' => ['call', 'webhook'],
            'record' => true,
            'metadata' => [
                'lead_id' => $payload['lead']['id'] ?? null,
                'contact_id' => $payload['lead']['contact_id'] ?? null,
                'source' => 'titannexus',
            ],
            'request_data' => $payload,
        ]);
    }

    protected function callLeadWithVapi(string $phone, array $payload): array
    {
        return $this->vapi->createCall([
            'assistantId' => config('titannexus_voice_ai.vapi.assistant_id'),
            'phoneNumberId' => config('titannexus_voice_ai.vapi.phone_number_id'),
            'customer' => [
                'number' => $phone,
                'name' => $payload['lead']['name'] ?? null,
            ],
            'metadata' => [
                'lead_id' => $payload['lead']['id'] ?? null,
                'contact_id' => $payload['lead']['contact_id'] ?? null,
                'source' => 'titannexus',
            ],
        ]);
    }

    protected function buildLeadPayload(array $lead): array
    {
        return [
            'lead' => [
                'id' => Arr::get($lead, 'id'),
                'contact_id' => Arr::get($lead, 'contact_id'),
                'name' => Arr::get($lead, 'name'),
                'company' => Arr::get($lead, 'company'),
                'email' => Arr::get($lead, 'email'),
                'phone' => Arr::get($lead, 'phone'),
                'status' => Arr::get($lead, 'status'),
                'service_vertical' => Arr::get($lead, 'vertical'),
                'qualification_score' => Arr::get($lead, 'score'),
                'notes' => Arr::get($lead, 'notes'),
            ],
            'operator' => [
                'company_name' => config('app.name'),
                'timestamp' => now()->toDateTimeString(),
            ],
            'instructions' => [
                'confirm_service_need',
                'identify_decision_maker',
                'confirm_timing',
                'record_objections',
                'request_booking_step',
            ],
        ];
    }
}
