<?php

namespace App\Extensions\MarketingBot\System\Services\Voice\Calls;

use App\Extensions\MarketingBot\System\Models\Voice\Call;
use App\Extensions\MarketingBot\System\Models\Voice\CallEvent;
use App\Extensions\MarketingBot\System\Models\Voice\CallRecording;

class CallIngestService
{
    public function upsertCall(array $attrs): Call
    {
        $sid = $attrs['provider_call_sid'] ?? null;

        $call = null;
        if ($sid) {
            $call = Call::query()->where('provider_call_sid', $sid)->first();
        }
        if (!$call) {
            $call = new Call();
        }

        $call->fill($attrs);
        $call->save();

        return $call;
    }

    public function addEvent(Call $call, string $type, string $name, array $payload = [], $occurredAt = null): CallEvent
    {
        return CallEvent::create([
            'call_id' => $call->id,
            'event_type' => $type,
            'event_name' => $name,
            'payload' => $payload ?: null,
            'occurred_at' => $occurredAt,
        ]);
    }

    public function addRecording(Call $call, array $attrs): CallRecording
    {
        $recSid = $attrs['provider_recording_sid'] ?? null;

        $rec = null;
        if ($recSid) {
            $rec = CallRecording::query()->where('provider_recording_sid', $recSid)->first();
        }
        if (!$rec) {
            $rec = new CallRecording();
        }

        $rec->fill(['call_id' => $call->id] + $attrs);
        $rec->save();

        return $rec;
    }
}
