<?php

namespace Modules\TitanNexus\Http\Middleware;

class EnsureTitanNexusEnabled
{
    public function handle($request, \Closure $next){ abort_unless(config("titan-nexus.features.api",true),404); return $next($request); }
}
