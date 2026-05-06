<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protects module administration routes via the `titan.admin` gate.
 *
 * Usage in routes: ->middleware('module.admin')
 */
class EnsureModuleAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Gate::allows('titan.admin')) {
            abort(403, 'Access to module administration is restricted.');
        }

        return $next($request);
    }
}
