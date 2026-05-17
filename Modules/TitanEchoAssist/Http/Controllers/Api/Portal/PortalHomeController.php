<?php

namespace Modules\TitanEchoAssist\Http\Controllers\Api\Portal;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\TitanEchoAssist\Models\Chatbot;
use Modules\TitanEchoAssist\Services\ChatbotPortalWidgetMenuService;

class PortalHomeController extends PortalBaseController
{
    public function __construct(private readonly ChatbotPortalWidgetMenuService $menuService) {}

    public function home(Request $request, Chatbot $chatbot): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);

        $upcomingVisits = $this->scopedQuery($chatbot, 'service_jobs')?->count()
            ?? $this->scopedQuery($chatbot, 'jobs')?->count()
            ?? 0;

        $outstandingInvoices = $this->scopedQuery($chatbot, 'invoices')?->count() ?? 0;
        $unreadNotifications = $this->scopedQuery($chatbot, 'ext_chatbot_portal_notifications')?->whereNull('read_at')->count() ?? 0;

        return response()->json([
            'upcoming_visits_count' => (int) $upcomingVisits,
            'outstanding_invoices_count' => (int) $outstandingInvoices,
            'notifications_unread_count' => (int) $unreadNotifications,
        ]);
    }

    public function menu(): JsonResponse
    {
        return response()->json($this->menuService->menu());
    }

    public function dashboard(Request $request, Chatbot $chatbot): JsonResponse
    {
        $chatbot = $this->portalChatbot($request, $chatbot);

        $upcoming = $this->scopedQuery($chatbot, 'service_jobs')?->limit(5)->get()
            ?? $this->scopedQuery($chatbot, 'jobs')?->limit(5)->get()
            ?? collect();

        return response()->json([
            'stats' => [
                'open_visits' => (int) ($this->scopedQuery($chatbot, 'service_jobs')?->count() ?? 0),
                'open_invoices' => (int) ($this->scopedQuery($chatbot, 'invoices')?->count() ?? 0),
                'open_issues' => (int) ($this->scopedQuery($chatbot, 'tickets')?->count() ?? 0),
            ],
            'upcoming_visits' => $upcoming->values(),
        ]);
    }
}
