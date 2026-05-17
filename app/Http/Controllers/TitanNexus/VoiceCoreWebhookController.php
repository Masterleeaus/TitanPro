<?php

namespace App\Http\Controllers\TitanNexus;

use App\Http\Controllers\Controller;
use App\Services\TitanNexus\VoiceCore\NexusCallSessionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VoiceCoreWebhookController extends Controller
{
    public function twilio(Request $request, NexusCallSessionService $service): JsonResponse
    {
        $session = $service->upsertFromWebhook($request->all(), 'twilio');

        return response()->json(['ok' => true, 'call_session_id' => $session->id]);
    }

    public function generic(Request $request, NexusCallSessionService $service): JsonResponse
    {
        $session = $service->upsertFromWebhook($request->all(), (string) $request->input('provider', 'generic'));

        return response()->json(['ok' => true, 'call_session_id' => $session->id]);
    }
}
