<?php

namespace Modules\ExampleModule\Http\Middleware;

class EnsureExampleModuleEnabled
{
    public function handle($request, \Closure $next){ abort_unless(config("example-module.features.api",true),404); return $next($request); }
}
