<?php

namespace Modules\CallingAgent\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CallingAgent\Models\CallingAgentCall;
use Modules\CallingAgent\Services\ReceptionistOrchestrator;
use Modules\CallingAgent\Services\TwilioChannelService;
use Modules\CallingAgent\Support\TenantContext;

class CallingAgentApiController extends Controller
{
    public function showCall(CallingAgentCall $call): CallingAgentCall
    {
        $tenantId = TenantContext::id();

        if ($tenantId !== null && $call->tenant_id !== null && $call->tenant_id !== $tenantId) {
            abort(404);
        }

        return $call->load([]);
    }

    public function sendSms(Request $request, TwilioChannelService $twilio): array
    {
        return $twilio->sendSms(
            (string) $request->string('to'),
            (string) $request->string('body'),
            $request->input('from')
        );
    }

    public function sendWhatsapp(Request $request, TwilioChannelService $twilio): array
    {
        return $twilio->sendWhatsapp(
            (string) $request->string('to'),
            (string) $request->string('body'),
            $request->input('from')
        );
    }

    /**
     * Initiate an outbound call via Twilio and persist a call log.
     */
    public function placeCall(Request $request, TwilioChannelService $twilio): JsonResponse
    {
        $validated = $request->validate([
            'to'               => 'required|string',
            'from'             => 'nullable|string',
            'twiml_url'        => 'required|url',
            'status_callback'  => 'nullable|url',
        ]);

        $result = $twilio->placeCall(
            $validated['to'],
            $validated['twiml_url'],
            $validated['from'] ?? null,
            $validated['status_callback'] ?? null
        );

        // Persist the outbound call log
        $call = CallingAgentCall::create([
            'tenant_id' => TenantContext::id($validated),
            'provider' => 'twilio',
            'call_sid' => $result['sid'],
            'direction' => 'outbound',
            'from' => $result['from'],
            'to' => $result['to'],
            'status' => $result['status'] ?? 'queued',
            'started_at' => now(),
            'metadata' => $result,
        ]);

        return response()->json(['call_id' => $call->id, 'call_sid' => $call->call_sid, 'status' => $call->status]);
    }

    /**
     * Transfer a live call to a target number / SIP URI.
     */
    public function transfer(Request $request, TwilioChannelService $twilio, string $callSid): JsonResponse
    {
        $validated = $request->validate([
            'target' => 'required|string',
        ]);

        if (!$twilio->isAvailable()) {
            // SDK not installed or credentials missing — log attempt but succeed gracefully
            \Log::warning('CallingAgent: transfer attempted but Twilio SDK unavailable', [
                'call_sid' => $callSid,
                'target'   => $validated['target'],
            ]);
            // Still persist the attempt
            $this->persistTransferAttempt($callSid, $validated['target'], 'sdk-unavailable');
            $this->mirrorEscalationToTitanHello($callSid, $validated['target'], 'sdk-unavailable');
            return response()->json([
                'success' => false,
                'error'   => 'Twilio SDK or credentials not available',
                'call_sid'=> $callSid,
            ], 422);
        }

        // Generate a Dial TwiML and update the call via Twilio REST
        $twiml = '<Response><Dial>' . e($validated['target']) . '</Dial></Response>';

        try {
            $twilio->client()->calls($callSid)->update([
                'twiml' => $twiml,
            ]);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }

        // Persist transfer attempt
        $this->persistTransferAttempt($callSid, $validated['target'], 'initiated');
        $this->mirrorEscalationToTitanHello($callSid, $validated['target'], 'initiated');

        return response()->json(['success' => true, 'call_sid' => $callSid, 'target' => $validated['target']]);
    }

    /**
     * Hang up a live call.
     */
    public function hangup(Request $request, TwilioChannelService $twilio, ReceptionistOrchestrator $orchestrator, string $callSid): JsonResponse
    {
        if (!$twilio->isAvailable()) {
            \Log::warning('CallingAgent: hangup attempted but Twilio SDK unavailable', ['call_sid' => $callSid]);
            $orchestrator->complete($callSid, ['CallStatus' => 'completed', 'CallSid' => $callSid]);
            return response()->json([
                'success' => false,
                'error'   => 'Twilio SDK or credentials not available',
                'call_sid'=> $callSid,
            ], 422);
        }

        try {
            $twilio->client()->calls($callSid)->update(['status' => 'completed']);
        } catch (\Throwable $e) {
            report($e);
            return response()->json(['success' => false, 'error' => $e->getMessage()], 422);
        }

        $orchestrator->complete($callSid, ['CallStatus' => 'completed', 'CallSid' => $callSid]);

        return response()->json(['success' => true, 'call_sid' => $callSid]);
    }

    // ── Private helpers ───────────────────────────────────────────────────────

    private function persistTransferAttempt(string $callSid, string $target, string $status): void
    {
        try {
            $call = CallingAgentCall::query()->withoutGlobalScopes()->where('call_sid', $callSid)->first();

            \DB::table('calling_agent_transfer_attempts')->insert([
                'tenant_id' => $call?->tenant_id ?? TenantContext::id(),
                'calling_agent_call_id' => $call?->id,
                'call_sid'      => $callSid,
                'target_number' => $target,
                'status'        => $status,
                'attempted_at'  => now(),
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        } catch (\Throwable $e) {
            report($e);
        }
    }

    private function mirrorEscalationToTitanHello(string $callSid, string $target, string $status): void
    {
        if (! class_exists(\Modules\TitanHello\Models\Call::class)) {
            return;
        }

        try {
            /** @var CallingAgentCall|null $callingAgentCall */
            $callingAgentCall = CallingAgentCall::query()->where('call_sid', $callSid)->first();

            /** @var \Modules\TitanHello\Models\Call $titanHelloCall */
            $titanHelloCall = \Modules\TitanHello\Models\Call::query()->firstOrNew([
                'provider' => 'twilio',
                'provider_call_sid' => $callSid,
            ]);

            $existingMeta = is_array($titanHelloCall->meta) ? $titanHelloCall->meta : [];

            $titanHelloCall->fill([
                'company_id' => $callingAgentCall?->tenant_id ?? auth()->user()?->organization_id,
                'direction' => $callingAgentCall?->direction ?? 'inbound',
                'from_number' => $callingAgentCall?->from,
                'to_number' => $callingAgentCall?->to ?? $target,
                'status' => 'escalated',
                'call_outcome' => 'human_escalation',
                'meta' => array_merge($existingMeta, [
                    'escalation_target' => $target,
                    'calling_agent_transfer_status' => $status,
                    'source' => 'calling-agent-transfer',
                ]),
            ]);

            $titanHelloCall->save();

            if (class_exists(\Modules\TitanHello\Events\CallStatusUpdated::class)) {
                event(new \Modules\TitanHello\Events\CallStatusUpdated($titanHelloCall));
            }
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
