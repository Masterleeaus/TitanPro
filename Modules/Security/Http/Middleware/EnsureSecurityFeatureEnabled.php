<?php

namespace Modules\Security\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSecurityFeatureEnabled
{
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $features = (array) config('security_features.features', []);

        abort_unless((bool) data_get($features, $feature, true), 404, 'Security feature is disabled.');

        return $next($request);
    }
}
