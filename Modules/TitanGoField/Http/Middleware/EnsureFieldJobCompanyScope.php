<?php

namespace Modules\TitanGoField\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\TitanGoField\Models\FieldJob;

class EnsureFieldJobCompanyScope
{
    public function handle(Request $request, Closure $next): mixed
    {
        $jobId = $request->route('job');

        if (! $jobId) {
            return $next($request);
        }

        $job  = FieldJob::find($jobId);
        $user = $request->user();

        if (! $job) {
            abort(404);
        }

        if (! $user || (int) $user->company_id !== (int) $job->company_id) {
            abort(403, 'Cross-tenant access denied.');
        }

        return $next($request);
    }
}
