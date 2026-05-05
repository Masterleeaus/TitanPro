<?php

namespace Modules\TitanNexus\Services\Voice;

use Modules\TitanNexus\Events\Lead\LeadCapturedFromVoice;

class VoiceLeadCaptureService
{
    public function handleWebhookPayload(array $payload): array
    {
        $lead = [
            'source' => 'voice',
            'provider' => 'twilio',
            'from' => $payload['From'] ?? $payload['from'] ?? null,
            'to' => $payload['To'] ?? $payload['to'] ?? null,
            'call_sid' => $payload['CallSid'] ?? $payload['call_sid'] ?? null,
            'body' => $payload['Body'] ?? $payload['SpeechResult'] ?? null,
            'raw' => $payload,
        ];

        event(new LeadCapturedFromVoice($lead));

        return ['ok' => true, 'lead' => $lead];
    }
}
