<?php

namespace App\Http\Controllers\TitanZero\Concerns;

use App\Models\TitanZeroThread;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

trait ResolvesTitanZeroThreads
{
    protected function findThreadOrFail(Request $request, string $threadId): TitanZeroThread
    {
        $organizationId = $request->user()?->organization_id;

        if ($organizationId === null) {
            throw new AccessDeniedHttpException('You do not have access to this thread.');
        }

        $thread = TitanZeroThread::query()
            ->withoutGlobalScopes()
            ->whereKey($threadId)
            ->firstOrFail();

        if ((string) $thread->organization_id !== (string) $organizationId) {
            throw new AccessDeniedHttpException('You do not have access to this thread.');
        }

        return $thread;
    }
}
