<?php
namespace Modules\CallingAgent\Services;

use Illuminate\Support\Facades\DB;
use Modules\CallingAgent\AI\Agents\ReceptionistAgent;
use Modules\CallingAgent\AI\Pipelines\OutcomeExtractionPipeline;
use Modules\CallingAgent\Models\CallingAgent;
use Modules\CallingAgent\Models\CallingAgentActiveCall;
use Modules\CallingAgent\Models\CallingAgentCall;
use Modules\CallingAgent\Models\CallingAgentCallerProfile;
use Modules\CallingAgent\Models\CallingAgentCallOutcome;
use Modules\CallingAgent\Models\CallingAgentPhoneNumber;
use Modules\CallingAgent\Models\CallingAgentTranscript;
use Modules\CallingAgent\Support\TenantContext;

class ReceptionistOrchestrator
{
    public function __construct(public ReceptionistAgent $agent) {}

    // -------------------------------------------------------------------------
    // Agent resolution
    // -------------------------------------------------------------------------

    public function resolveByNumber(?string $to): ?CallingAgent
    {
        if (!$to) {
            return null;
        }

        $tenantId = TenantContext::id(['to' => $to]);
        $phoneNumbers = CallingAgentPhoneNumber::query()->withoutGlobalScopes()->where('number', $to);

        if ($tenantId !== null) {
            $phoneNumbers->where('tenant_id', $tenantId);
        }

        $pn = $phoneNumbers->first();

        $agents = CallingAgent::query()->withoutGlobalScopes();

        if ($pn?->calling_agent_id) {
            $agents->whereKey($pn->calling_agent_id);
        } else {
            $agents->where('phone_number', $to);
        }

        if ($tenantId !== null) {
            $agents->where('tenant_id', $tenantId);
        }

        $agent = $agents->first();

        TenantContext::setTenantId($agent?->tenant_id ?? $pn?->tenant_id ?? $tenantId);

        return $agent;
    }

    // -------------------------------------------------------------------------
    // Call lifecycle
    // -------------------------------------------------------------------------

    public function startInbound(array $payload): CallingAgentCall
    {
        $callSid = $payload['CallSid'] ?? null;
        $agent = $this->resolveByNumber($payload['To'] ?? null);
        $tenantId = $agent?->tenant_id ?? TenantContext::id($payload);

        TenantContext::setTenantId($tenantId);

        return CallingAgentCall::updateOrCreate(
            ['call_sid' => $callSid],
            [
                'tenant_id' => $tenantId,
                'calling_agent_id' => $agent?->id,
                'provider' => 'twilio',
                'direction' => 'inbound',
                'from' => $payload['From'] ?? null,
                'to' => $payload['To'] ?? null,
                'status' => $payload['CallStatus'] ?? 'ringing',
                'started_at' => now(),
                'metadata' => $payload,
            ]
        );
    }

    public function touchActive(CallingAgentCall $call, array $payload): void
    {
        TenantContext::setTenantId($call->tenant_id ?? TenantContext::id($payload));

        CallingAgentActiveCall::updateOrCreate(
            ['call_sid' => $call->call_sid],
            [
                'tenant_id'              => $call->tenant_id,
                'calling_agent_call_id' => $call->id,
                'from'                   => $call->from,
                'to'                     => $call->to,
                'state'                  => $payload['CallStatus'] ?? 'ringing',
                'last_seen_at'           => now(),
                'context'                => $payload,
            ]
        );
    }

    // -------------------------------------------------------------------------
    // Speech/Gather turn
    // -------------------------------------------------------------------------

    public function answer(CallingAgentCall $call, string $speech, array $context = []): string
    {
        TenantContext::setTenantId($call->tenant_id);

        // Persist caller turn
        CallingAgentTranscript::create([
            'calling_agent_call_id' => $call->id,
            'role'                  => 'user',
            'text'                  => $speech,
            'source'                => 'twilio-speech',
        ]);

        // Enrich context with caller profile memory
        $profile = $this->recallCallerProfile($call->from);
        if ($profile) {
            $context['caller_profile'] = $profile->toArray();
        }

        $reply = $this->agent->respond($speech, $context);

        // Persist assistant turn
        CallingAgentTranscript::create([
            'calling_agent_call_id' => $call->id,
            'role'                  => 'assistant',
            'text'                  => $reply,
            'source'                => 'ai',
        ]);

        return $reply;
    }

    // -------------------------------------------------------------------------
    // Call completion + outcome
    // -------------------------------------------------------------------------

    public function complete(string $callSid, array $payload): void
    {
        $tenantId = TenantContext::id($payload);
        $callQuery = CallingAgentCall::query()->withoutGlobalScopes()->where('call_sid', $callSid);

        if ($tenantId !== null) {
            $callQuery->where('tenant_id', $tenantId);
        }

        $call = $callQuery->first();

        TenantContext::setTenantId($call?->tenant_id ?? $tenantId);

        if ($call) {
            $call->update([
                'tenant_id' => $call->tenant_id ?? $tenantId,
                'status' => $payload['CallStatus'] ?? 'completed',
                'duration' => (int) ($payload['CallDuration'] ?? $call->duration),
                'ended_at' => now(),
                'metadata' => array_merge($call->metadata ?? [], $payload),
            ]);

            // Extract outcome from transcript
            $this->extractAndPersistOutcome($call);

            // Update caller profile
            $this->updateCallerProfile($call);

            // Schedule missed-call recovery if unanswered
            $status = $payload['CallStatus'] ?? '';
            if (in_array($status, ['no-answer', 'busy', 'failed'], true)) {
                $this->scheduleMissedCallRecovery($call);
            }
        }

        $activeCalls = CallingAgentActiveCall::query()->withoutGlobalScopes()->where('call_sid', $callSid);

        if (($call?->tenant_id ?? $tenantId) !== null) {
            $activeCalls->where('tenant_id', $call?->tenant_id ?? $tenantId);
        }

        $activeCalls->delete();
    }

    // -------------------------------------------------------------------------
    // Idempotency guard for webhook events
    // -------------------------------------------------------------------------

    /**
     * Returns true if the event was already processed (duplicate / replay).
     * Marks the event as processed on first call.
     */
    public function isDuplicate(string $eventId, string $source = 'twilio'): bool
    {
        $tenantId = TenantContext::id();
        $scopedEventId = TenantContext::scopedEventId($source . ':' . $eventId, $tenantId);

        try {
            $inserted = DB::table('calling_agent_webhook_idempotency')->insertOrIgnore([
                'tenant_id' => $tenantId,
                'event_id' => $scopedEventId,
                'source' => $source,
                'processed_at' => now(),
                'payload_hash' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            // insertOrIgnore returns affected rows: 0 = already existed (duplicate)
            return $inserted === 0;
        } catch (\Throwable $e) {
            // Table absent (pre-migration): treat as non-duplicate, log error
            report($e);
            return false;
        }
    }

    // -------------------------------------------------------------------------
    // Caller profile memory
    // -------------------------------------------------------------------------

    public function recallCallerProfile(?string $phone): ?CallingAgentCallerProfile
    {
        if (!$phone) {
            return null;
        }

        $query = CallingAgentCallerProfile::query()->withoutGlobalScopes()->where('phone', $phone);

        if (($tenantId = TenantContext::id(['phone' => $phone])) !== null) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->first();
    }

    private function updateCallerProfile(CallingAgentCall $call): void
    {
        if (!$call->from) {
            return;
        }

        try {
            CallingAgentCallerProfile::updateOrCreate(
                [
                    'tenant_id' => $call->tenant_id,
                    'phone' => $call->from,
                ],
                [
                    'tenant_id' => $call->tenant_id,
                    'last_call_at' => now(),
                    'last_seen_at' => now(),
                    'call_count' => DB::raw('COALESCE(call_count, 0) + 1'),
                ]
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }

    // -------------------------------------------------------------------------
    // Outcome extraction
    // -------------------------------------------------------------------------

    private function extractAndPersistOutcome(CallingAgentCall $call): void
    {
        try {
            $transcripts = CallingAgentTranscript::where('calling_agent_call_id', $call->id)
                ->orderBy('id')
                ->pluck('text')
                ->toArray();

            if (empty($transcripts)) {
                return;
            }

            $pipeline = new OutcomeExtractionPipeline();
            $outcome = $pipeline->extract($transcripts);

            CallingAgentCallOutcome::updateOrCreate(
                ['call_sid' => $call->call_sid],
                array_merge($outcome->toArray(), [
                    'tenant_id' => $call->tenant_id,
                    'raw' => $outcome->toArray(),
                ])
            );
        } catch (\Throwable $e) {
            report($e);
        }
    }

    // -------------------------------------------------------------------------
    // Missed-call recovery
    // -------------------------------------------------------------------------

    private function isMissingTableException(\Throwable $e): bool
    {
        if ($e instanceof \Illuminate\Database\QueryException) {
            // MySQL: 1146, SQLite: general "no such table", PostgreSQL: 42P01
            $code = (string) $e->getCode();
            if (in_array($code, ['1146', '42P01', 'HY000'], true)) {
                return true;
            }
        }
        $msg = strtolower($e->getMessage());
        return str_contains($msg, "doesn't exist")
            || str_contains($msg, 'no such table')
            || str_contains($msg, 'does not exist');
    }

    private function scheduleMissedCallRecovery(CallingAgentCall $call): void
    {
        try {
            DB::table('calling_agent_missed_call_recovery_tasks')->insertOrIgnore([
                'tenant_id'             => $call->tenant_id,
                'calling_agent_call_id' => $call->id,
                'call_sid' => $call->call_sid,
                'phone' => $call->from,
                'channel' => 'sms',
                'status' => 'pending',
                'attempts' => 0,
                'scheduled_at' => now()->addMinutes(5),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            if (!$this->isMissingTableException($e)) {
                report($e);
            }
        }
    }
}
