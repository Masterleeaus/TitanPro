<?php

namespace App\Services\TitanNexus\VoiceCore;

use App\Models\TitanNexus\NexusCallEvent;
use App\Models\TitanNexus\NexusCallRecording;
use App\Models\TitanNexus\NexusCallSession;
use App\Models\TitanNexus\NexusCallbackRequest;
use Illuminate\Support\Arr;

class NexusCallSessionService
{
    public function __construct(protected NexusLeadExtractionService $leadExtractor) {}

    public function upsertFromWebhook(array $payload, string $provider = 'twilio'): NexusCallSession
    {
        $providerCallId = Arr::get($payload, 'CallSid') ?: Arr::get($payload, 'call_id') ?: Arr::get($payload, 'id') ?: uniqid('call_', true);

        $leadId = $this->leadExtractor->extractFromCall([
            'from_number' => Arr::get($payload, 'From') ?: Arr::get($payload, 'from') ?: Arr::get($payload, 'customer.number'),
            'phone' => Arr::get($payload, 'From') ?: Arr::get($payload, 'from') ?: Arr::get($payload, 'customer.number'),
            'name' => Arr::get($payload, 'CallerName') ?: Arr::get($payload, 'name'),
            'company' => Arr::get($payload, 'company'),
            'email' => Arr::get($payload, 'email'),
            'raw' => $payload,
        ]);

        $session = NexusCallSession::updateOrCreate(
            ['provider' => $provider, 'provider_call_id' => $providerCallId],
            [
                'direction' => Arr::get($payload, 'Direction') ?: Arr::get($payload, 'direction'),
                'lead_id' => $leadId,
                'from_number' => Arr::get($payload, 'From') ?: Arr::get($payload, 'from'),
                'to_number' => Arr::get($payload, 'To') ?: Arr::get($payload, 'to'),
                'status' => Arr::get($payload, 'CallStatus') ?: Arr::get($payload, 'status', 'updated'),
                'outcome' => Arr::get($payload, 'outcome'),
                'duration_seconds' => Arr::get($payload, 'CallDuration') ?: Arr::get($payload, 'duration_seconds'),
                'summary' => Arr::get($payload, 'summary'),
                'transcript' => Arr::get($payload, 'transcript'),
                'payload' => $payload,
                'started_at' => Arr::get($payload, 'started_at') ?: now(),
                'ended_at' => Arr::get($payload, 'ended_at'),
            ]
        );

        $this->storeEvent($session, $payload, $provider);

        if (Arr::get($payload, 'RecordingUrl') || Arr::get($payload, 'recording_url')) {
            $this->storeRecording($session, $payload, $provider);
        }

        if ($this->needsCallback($payload)) {
            $this->createCallback($session, $payload);
        }

        return $session;
    }

    protected function storeEvent(NexusCallSession $session, array $payload, string $provider): void
    {
        NexusCallEvent::create([
            'call_session_id' => $session->id,
            'provider' => $provider,
            'provider_event_id' => Arr::get($payload, 'SmsSid') ?: Arr::get($payload, 'event_id'),
            'event_type' => Arr::get($payload, 'EventType') ?: Arr::get($payload, 'CallStatus') ?: Arr::get($payload, 'status', 'webhook'),
            'status' => Arr::get($payload, 'CallStatus') ?: Arr::get($payload, 'status'),
            'payload' => $payload,
        ]);
    }

    protected function storeRecording(NexusCallSession $session, array $payload, string $provider): void
    {
        NexusCallRecording::updateOrCreate(
            [
                'call_session_id' => $session->id,
                'provider_recording_id' => Arr::get($payload, 'RecordingSid') ?: Arr::get($payload, 'recording_id'),
            ],
            [
                'provider' => $provider,
                'recording_url' => Arr::get($payload, 'RecordingUrl') ?: Arr::get($payload, 'recording_url'),
                'duration_seconds' => Arr::get($payload, 'RecordingDuration') ?: Arr::get($payload, 'duration_seconds'),
                'status' => 'available',
                'expires_at' => now()->addDays(config('titannexus_voice_core.recording_retention_days', 90)),
                'payload' => $payload,
            ]
        );
    }

    protected function needsCallback(array $payload): bool
    {
        $status = strtolower((string) (Arr::get($payload, 'CallStatus') ?: Arr::get($payload, 'status')));
        $outcome = strtolower((string) Arr::get($payload, 'outcome'));

        return in_array($status, ['no-answer', 'busy', 'failed'], true)
            || str_contains($outcome, 'callback')
            || (bool) Arr::get($payload, 'callback_required');
    }

    protected function createCallback(NexusCallSession $session, array $payload): void
    {
        NexusCallbackRequest::create([
            'lead_id' => $session->lead_id,
            'contact_id' => $session->contact_id,
            'call_session_id' => $session->id,
            'name' => Arr::get($payload, 'CallerName') ?: Arr::get($payload, 'name'),
            'phone' => $session->from_number ?: $session->to_number,
            'status' => 'open',
            'priority' => 'normal',
            'due_at' => now()->addMinutes(config('titannexus_voice_core.callback_due_minutes', 30)),
            'notes' => Arr::get($payload, 'summary') ?: 'Callback required from call outcome.',
            'payload' => $payload,
        ]);
    }
}
