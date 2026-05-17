<?php

namespace Modules\ZeroPayModule\Http\Middleware;

class EnsureZeroPayModuleEnabled
{
    public function handle($request, \Closure $next)
    {
        abort_unless(config('zeropay-module.features.api', true), 404);

        return $next($request);
    }
}
