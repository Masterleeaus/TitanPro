<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Models\Chatbot;

class PortalNotificationController extends PortalBaseController
{
    public function index(Request $request, Chatbot $chatbot, int $customerId): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $notifications = $this->companyScopedByCustomer($chatbot, 'ext_chatbot_portal_notifications', $customerId)?->orderByDesc('id')?->paginate($request->integer('per_page', 20));

        return response()->json($notifications ?? ['data' => []]);
    }

    public function markRead(Request $request, Chatbot $chatbot, int $id): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);
        $updated = $this->scopedQuery($chatbot, 'ext_chatbot_portal_notifications')?->where('id', $id)?->update(['read_at' => now()]);

        return response()->json([
            'ok' => true,
            'updated' => (bool) $updated,
        ]);
    }
}
