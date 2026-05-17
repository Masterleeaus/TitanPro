<?php

namespace Modules\ZeroPayModule\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ZeroPayModule\Models\Scopes\TenantScope;
use Modules\ZeroPayModule\Models\ZeroPayNotification;

class NotificationApiController extends Controller
{
    /**
     * List notifications for the authenticated user (paginated).
     */
    public function index(Request $request): JsonResponse
    {
        $notifications = ZeroPayNotification::withoutGlobalScope(TenantScope::class)
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return response()->json($notifications);
    }

    /**
     * Mark a specific notification as read.
     */
    public function markRead(Request $request, int $id): JsonResponse
    {
        $notification = ZeroPayNotification::withoutGlobalScope(TenantScope::class)
            ->where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $notification->update(['status' => 'read', 'read_at' => now()]);

        return response()->json($notification->fresh());
    }
}
