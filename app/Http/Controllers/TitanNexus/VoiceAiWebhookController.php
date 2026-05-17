<?php

namespace App\Http\Controllers\TitanNexus;

use App\Http\Controllers\Controller;
use App\Models\TitanNexus\NexusVoiceCallLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class VoiceAiWebhookController extends Controller
{
    public function bland(Request $request): JsonResponse
    {
        $payload = $request->all();
        $callId = Arr::get($payload, 'call_id') ?: Arr::get($payload, 'call.call_id') ?: Arr::get($payload, 'id');
        $metadata = Arr::get($payload, 'metadata', []);

        NexusVoiceCallLog::updateOrCreate(
            ['provider' => 'bland', 'call_id' => $callId],
            [
                'direction' => Arr::get($payload, 'direction', 'outbound'),
                'lead_id' => Arr::get($metadata, 'lead_id'),
                'contact_id' => Arr::get($metadata, 'contact_id'),
                'phone_number' => Arr::get($payload, 'to') ?: Arr::get($payload, 'phone_number'),
                'status' => Arr::get($payload, 'status') ?: Arr::get($payload, 'call.status'),
                'duration_seconds' => Arr::get($payload, 'duration'),
                'summary' => Arr::get($payload, 'summary'),
                'transcript' => Arr::get($payload, 'transcript'),
                'recording_url' => Arr::get($payload, 'recording_url'),
                'webhook_payload' => $payload,
                'ended_at' => now(),
            ]
        );

        return response()->json(['ok' => true]);
    }

    public function vapiInbound(Request $request): JsonResponse
    {
        return $this->storeVapiWebhook($request, 'inbound');
    }

    public function vapiOutbound(Request $request): JsonResponse
    {
        return $this->storeVapiWebhook($request, 'outbound');
    }

    protected function storeVapiWebhook(Request $request, string $direction): JsonResponse
    {
        $payload = $request->all();
        $message = Arr::get($payload, 'message', $payload);
        $call = Arr::get($message, 'call', []);
        $metadata = Arr::get($call, 'metadata', []);

        $callId = Arr::get($call, 'id') ?: Arr::get($message, 'callId') ?: Arr::get($payload, 'call_id');

        NexusVoiceCallLog::updateOrCreate(
            ['provider' => 'vapi', 'call_id' => $callId],
            [
                'direction' => $direction,
                'lead_id' => Arr::get($metadata, 'lead_id'),
                'contact_id' => Arr::get($metadata, 'contact_id'),
                'phone_number' => Arr::get($call, 'customer.number'),
                'status' => Arr::get($message, 'type') ?: Arr::get($call, 'status'),
                'duration_seconds' => Arr::get($call, 'durationSeconds'),
                'summary' => Arr::get($message, 'summary'),
                'transcript' => Arr::get($message, 'transcript'),
                'recording_url' => Arr::get($message, 'recordingUrl') ?: Arr::get($call, 'recordingUrl'),
                'webhook_payload' => $payload,
                'ended_at' => now(),
            ]
        );

        return response()->json(['ok' => true]);
    }
}
