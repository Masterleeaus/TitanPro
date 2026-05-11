<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Extensions\TitanLeads\System\Services\LeadMailbox\InboundIngestService;
use App\Extensions\TitanLeads\System\Models\MessengerChannel;
use Illuminate\Http\Request;

class MessengerWebhookController extends Controller
{
    /**
     * Verification handshake (GET) for Meta webhooks.
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        // Find an active channel verify token match (scoped by tenant later if you have subdomains).
        $channel = MessengerChannel::query()
            ->where('active', true)
            ->whereNotNull('verify_token')
            ->where('verify_token', $token)
            ->first();

        if ($mode === 'subscribe' && $channel) {
            return response($challenge, 200);
        }

        return response('Forbidden', 403);
    }

    /**
     * Inbound message webhook (POST) for Meta.
     * Normalizes to Titan Leads conversation ingestion.
     */
    public function inbound(Request $request)
    {
        // NOTE: You can add signature verification (X-Hub-Signature-256) in Governance pass.
        $payload = $request->all();

        // Minimal normalization for MVP:
        // Meta sends entries -> messaging events. We'll capture text if present.
        $events = [];

        foreach (($payload['entry'] ?? []) as $entry) {
            foreach (($entry['messaging'] ?? []) as $msg) {
                $senderId = $msg['sender']['id'] ?? null;
                $recipientId = $msg['recipient']['id'] ?? null;
                $text = $msg['message']['text'] ?? null;

                if ($senderId && $text) {
                    $events[] = [
                        'channel' => 'messenger',
                        'from' => $senderId,
                        'to' => $recipientId,
                        'body' => $text,
                        'raw' => $msg,
                    ];
                }
            }
        }

        foreach ($events as $event) {
            InboundIngestService::ingest($event);
        }

        return response('OK', 200);
    }
}
