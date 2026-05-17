<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Models\Chatbot;

class PortalRecurringController extends PortalBaseController
{
    public function update(Request $request, Chatbot $chatbot, int $customerId): JsonResponse
    {
        $validated = $request->validate([
            'enabled' => 'required|boolean',
            'frequency' => 'nullable|string|max:100',
            'preferences' => 'nullable|array',
        ]);

        return response()->json([
            'ok' => true,
            'chatbot_id' => $chatbot->getKey(),
            'customer_id' => $customerId,
            'recurring' => $validated,
        ]);
    }
}
