<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Webhook;

use App\Extensions\TitanLeads\System\Models\VoiceCall;
use App\Extensions\TitanLeads\System\Models\VoiceTranscript;
use Illuminate\Http\Request;

class VoiceTranscriptionWebhookController
{
    public function __invoke(Request $request)
    {
        $callSid = $request->input('CallSid');
        $transcript = $request->input('TranscriptionText') ?? $request->input('transcript');

        if (!$callSid) {
            return response('Missing CallSid', 422);
        }

        $call = VoiceCall::query()->where('call_sid', $callSid)->first();
        if (!$call) {
            return response('Call not found', 404);
        }

        VoiceTranscript::query()->create([
            'voice_call_id' => $call->getKey(),
            'transcript' => $transcript,
            'provider_payload' => $request->all(),
        ]);

        return response('OK', 200);
    }
}
