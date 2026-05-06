<?php

namespace Extensions\TitanNexus\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Extensions\TitanNexus\Models\TitanNexusCallSession;
use Extensions\TitanNexus\Models\TitanNexusCallEvent;
use Extensions\TitanNexus\Models\TitanNexusLead;
use Extensions\TitanNexus\Models\ExtVoicechatbotHistory;
use Extensions\TitanNexus\Models\ExtVoicechabotConversation;
use Extensions\TitanNexus\Services\LeadExtractionService;

class InternalEventsController extends Controller
{
    /**
     * WS bridge posts transcript chunks and lifecycle events here.
     * Protected by a shared secret header: X-TitanNexus-Secret
     */
    public function transcript(Request $request)
    {
        $secret = (string) config('titan-hello.security.bridge_shared_secret', '');
        if ($secret !== '' && $request->header('X-TitanNexus-Secret') !== $secret) {
            return response()->json(['ok' => false, 'error' => 'forbidden'], 403);
        }

        $callSid = (string) $request->input('call_sid', '');
        $role = (string) $request->input('role', 'assistant');
        $message = (string) $request->input('message', '');

        if ($callSid === '' || $message === '') {
            return response()->json(['ok' => false, 'error' => 'missing_fields'], 422);
        }

        $session = TitanNexusCallSession::query()->where('call_sid', $callSid)->first();
        if (!$session) {
            return response()->json(['ok' => false, 'error' => 'call_not_found'], 404);
        }

        // Ensure a conversation exists.
        $conversation = null;
        if ($session->conversation_db_id) {
            $conversation = ExtVoicechabotConversation::query()->find($session->conversation_db_id);
        }

        if (!$conversation) {
            $agent = $session->agent_id ? \Extensions\TitanNexus\Models\ExtVoiceChatbot::query()->find($session->agent_id) : null;
            if ($agent) {
                $conversation = ExtVoicechabotConversation::query()->create([
                    'chatbot_uuid' => $agent->uuid,
                    'conversation_id' => $callSid,
                ]);
                $session->conversation_db_id = $conversation->id;
                $session->save();
            }
        }

        if ($conversation) {
            ExtVoicechatbotHistory::query()->create([
                'conversation_id' => $conversation->id,
                'role' => $role,
                'message' => $message,
            ]);
        }

        TitanNexusCallEvent::query()->create([
            'call_session_id' => $session->id,
            'type' => 'transcript',
            'payload' => [
                'role' => $role,
                'message' => mb_substr($message, 0, 5000),
            ],
        ]);


        // Auto-extract basic lead fields from transcript (rule-based)
        app(LeadExtractionService::class)->extractAndUpdate($session);
        // Optional: allow bridge/agent to pass structured lead fields alongside transcript.
        $lead = $request->input('lead');
        if (is_array($lead)) {
            $this->upsertLeadFromArray($session, $lead);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * Bridge/agent can send structured updates at any time.
     * POST /api/titan-hello/internal/lead
     */
    public function lead(Request $request)
    {
        $secret = (string) config('titan-hello.security.bridge_shared_secret', '');
        if ($secret !== '' && $request->header('X-TitanNexus-Secret') !== $secret) {
            return response()->json(['ok' => false, 'error' => 'forbidden'], 403);
        }

        $callSid = (string) $request->input('call_sid', '');
        $lead = $request->input('lead');
        if ($callSid === '' || !is_array($lead)) {
            return response()->json(['ok' => false, 'error' => 'missing_fields'], 422);
        }

        $session = TitanNexusCallSession::query()->where('call_sid', $callSid)->first();
        if (!$session) {
            return response()->json(['ok' => false, 'error' => 'call_not_found'], 404);
        }

        $this->upsertLeadFromArray($session, $lead);

        TitanNexusCallEvent::query()->create([
            'call_session_id' => $session->id,
            'type' => 'lead_update',
            'payload' => $lead,
        ]);

        return response()->json(['ok' => true]);
    }

    private function upsertLeadFromArray(TitanNexusCallSession $session, array $lead): void
    {
        $fields = [
            'caller_name' => $lead['caller_name'] ?? null,
            'caller_phone' => $lead['caller_phone'] ?? $session->from_number ?? null,
            'suburb' => $lead['suburb'] ?? null,
            'job_type' => $lead['job_type'] ?? null,
            'urgency' => $lead['urgency'] ?? null,
            'callback_window' => $lead['callback_window'] ?? null,
            'notes' => $lead['notes'] ?? null,
        ];

        // Update call_session convenience columns (helps dashboards without joins)
        foreach (['caller_name','job_type','suburb','urgency','callback_window'] as $col) {
            if (isset($fields[$col]) && is_string($fields[$col]) && $fields[$col] !== '') {
                $session->{$col} = $fields[$col];
            }
        }
        $session->save();

        TitanNexusLead::query()->updateOrCreate(
            ['call_session_id' => $session->id],
            $fields
        );
    }
}
