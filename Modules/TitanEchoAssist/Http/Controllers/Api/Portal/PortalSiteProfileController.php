<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Models\Chatbot;

class PortalSiteProfileController extends PortalBaseController
{
    public function store(Request $request, Chatbot $chatbot): JsonResponse
    {
        $validated = $request->validate([
            'site_id' => 'required|integer',
            'profile' => 'required|array',
        ]);

        return response()->json([
            'ok' => true,
            'chatbot_id' => $chatbot->getKey(),
            'site_id' => $validated['site_id'],
            'profile' => $validated['profile'],
        ], 201);
    }

    public function show(Request $request, Chatbot $chatbot, int $siteId): JsonResponse
    {
        return response()->json([
            'site_id' => $siteId,
            'chatbot_id' => $chatbot->getKey(),
            'company_id' => $chatbot->company_id,
        ]);
    }
}
