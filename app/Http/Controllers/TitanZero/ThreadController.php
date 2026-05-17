<?php

namespace App\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Models\TitanZeroThread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * GET /api/titan/threads/{threadId}
 *
 * Returns the persisted messages and latest widget set for a single conversation
 * thread, scoped to the authenticated user's organisation.
 */
class ThreadController extends Controller
{
    public function show(Request $request, string $threadId): JsonResponse
    {
        $thread = TitanZeroThread::query()
            ->withoutGlobalScopes()
            ->whereKey($threadId)
            ->firstOrFail();

        if ((int) $thread->organization_id !== (int) $request->user()?->organization_id) {
            throw new AccessDeniedHttpException('You do not have access to this thread.');
        }

        return response()->json([
            'messages' => $thread->messages ?? [],
            'widgets'  => $thread->widgets  ?? [],
        ]);
    }
}
