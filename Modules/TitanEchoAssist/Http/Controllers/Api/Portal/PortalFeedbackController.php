<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Models\Chatbot;

class PortalFeedbackController extends PortalBaseController
{
    public function store(Request $request, Chatbot $chatbot): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|in:feedback,reclean',
            'message' => 'required|string|max:2000',
            'rating' => 'nullable|integer|min:1|max:5',
        ]);

        return response()->json([
            'ok' => true,
            'chatbot_id' => $chatbot->getKey(),
            'feedback' => $validated,
        ], 201);
    }
}
