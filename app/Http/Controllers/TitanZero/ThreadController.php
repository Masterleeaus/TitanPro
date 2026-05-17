<?php

namespace App\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Models\TitanZeroThread;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
            ->where('id', $threadId)
            ->firstOrFail();

        return response()->json([
            'messages' => $thread->messages ?? [],
            'widgets'  => $thread->widgets  ?? [],
        ]);
    }
}
