<?php

namespace Modules\ZeroPayModule\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ZeroPayModule\Models\ZeroPayPushSubscription;

class ZeroPayPushController extends Controller
{
    /**
     * Store or update a Web Push subscription for the authenticated user.
     *
     * Expected body:
     * {
     *   "subscription": {
     *     "endpoint": "https://...",
     *     "keys": { "p256dh": "...", "auth": "..." },
     *     "contentEncoding": "aesgcm"   // optional
     *   }
     * }
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'subscription' => 'required|array',
            'subscription.endpoint' => 'required|url',
            'subscription.keys' => 'required|array',
            'subscription.keys.p256dh' => 'required|string',
            'subscription.keys.auth' => 'required|string',
            'subscription.contentEncoding' => 'sometimes|string',
        ]);

        $user = $request->user();
        $companyId = $user->company_id ?? null;

        ZeroPayPushSubscription::upsertFromJson(
            $user->id,
            $companyId,
            $validated['subscription']
        );

        return response()->json(['message' => 'Subscribed successfully.'], 201);
    }

    /**
     * Remove all push subscriptions for the authenticated user
     * (or only the subscription matching the provided endpoint).
     */
    public function unsubscribe(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'endpoint' => 'sometimes|url',
        ]);

        $query = ZeroPayPushSubscription::where('user_id', $request->user()->id);

        if (! empty($validated['endpoint'])) {
            $query->where('endpoint', $validated['endpoint']);
        }

        $query->delete();

        return response()->json(['message' => 'Unsubscribed successfully.']);
    }
}
