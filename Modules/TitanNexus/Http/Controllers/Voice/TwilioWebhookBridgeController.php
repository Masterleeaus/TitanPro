<?php

namespace Modules\TitanNexus\Http\Controllers\Voice;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\TitanNexus\Events\Voice\VoiceWebhookReceived;
use Modules\TitanNexus\Services\Voice\VoiceLeadCaptureService;

class TwilioWebhookBridgeController extends Controller
{
    public function __invoke(Request $request, VoiceLeadCaptureService $capture)
    {
        event(new VoiceWebhookReceived($request->all()));
        return response()->json($capture->handleWebhookPayload($request->all()));
    }
}
