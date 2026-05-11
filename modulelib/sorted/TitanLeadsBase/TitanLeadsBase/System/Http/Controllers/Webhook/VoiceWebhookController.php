<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Webhook;

use App\Extensions\TitanLeads\System\Models\VoiceCall;
use App\Extensions\TitanLeads\System\Models\VoiceChannel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VoiceWebhookController
{
    public function __invoke(Request $request)
    {
        $to = $request->input('To');
        $from = $request->input('From');
        $callSid = $request->input('CallSid');
        $status = $request->input('CallStatus');
        $direction = $request->input('Direction');

        $channel = VoiceChannel::query()
            ->where('is_active', true)
            ->where(function ($q) use ($to) {
                $q->where('from_number', $to)
                    ->orWhere('from_number', Str::replaceFirst('+', '', (string)$to));
            })
            ->first();

        if (!$channel) {
            return response('Voice channel not configured', 404);
        }

        VoiceCall::query()->updateOrCreate(
            ['call_sid' => (string)$callSid],
            [
                'user_id' => (int)$channel->user_id,
                'from_number' => (string)$from,
                'to_number' => (string)$to,
                'direction' => (string)$direction,
                'status' => (string)$status,
                'started_at' => now(),
            ]
        );

        // Minimal TwiML: speak a short acknowledgement
        $twiml = '<?xml version="1.0" encoding="UTF-8"?>'
            . '<Response><Say>Thanks. Please leave a message after the tone.</Say><Record maxLength="60" playBeep="true"/></Response>';

        return response($twiml, 200)->header('Content-Type', 'text/xml');
    }
}
