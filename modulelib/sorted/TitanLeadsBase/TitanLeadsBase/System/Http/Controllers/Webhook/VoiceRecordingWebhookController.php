<?php

namespace App\Extensions\TitanLeads\System\Http\Controllers\Webhook;

use App\Extensions\TitanLeads\System\Models\VoiceCall;
use Illuminate\Http\Request;

class VoiceRecordingWebhookController
{
    public function __invoke(Request $request)
    {
        $callSid = $request->input('CallSid');
        $recordingUrl = $request->input('RecordingUrl');
        $duration = $request->input('RecordingDuration');

        if (!$callSid) {
            return response('Missing CallSid', 422);
        }

        VoiceCall::query()->where('call_sid', $callSid)->update([
            'recording_url' => $recordingUrl,
            'duration' => $duration ? (int)$duration : null,
            'ended_at' => now(),
        ]);

        return response('OK', 200);
    }
}
