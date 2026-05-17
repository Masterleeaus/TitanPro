<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Jobs\ExecutePortalActionJob;
use Modules\TitanEchoAssist\Models\Chatbot;

class PortalActionController extends PortalBaseController
{
    public function index(): JsonResponse
    {
        return response()->json([
            'actions' => [
                ['key' => 'book_visit', 'label' => 'Book a visit'],
                ['key' => 'approve_quote', 'label' => 'Approve quote'],
                ['key' => 'pay_invoice', 'label' => 'Pay invoice'],
                ['key' => 'request_reclean', 'label' => 'Request reclean'],
            ],
        ]);
    }

    public function store(Request $request, Chatbot $chatbot, string $sessionId): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|string|in:book_visit,approve_quote,pay_invoice,request_reclean',
            'payload' => 'nullable|array',
        ]);

        ExecutePortalActionJob::dispatch([
            'chatbot_id' => $chatbot->getKey(),
            'company_id' => $chatbot->company_id,
            'session_id' => $sessionId,
            'action' => $validated['action'],
            'payload' => $validated['payload'] ?? [],
        ]);

        return response()->json([
            'ok' => true,
            'queued' => true,
            'action' => $validated['action'],
        ], 202);
    }
}
