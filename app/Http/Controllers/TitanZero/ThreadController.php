<?php

namespace App\Http\Controllers\TitanZero;

use App\Http\Controllers\Controller;
use App\Http\Controllers\TitanZero\Concerns\ResolvesTitanZeroThreads;
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
    use ResolvesTitanZeroThreads;

    public function show(Request $request, string $threadId): JsonResponse
    {
        $thread = $this->findThreadOrFail($request, $threadId);

        return response()->json([
            'messages' => $thread->messages ?? [],
            'widgets'  => $thread->widgets  ?? [],
        ]);
    }
}
