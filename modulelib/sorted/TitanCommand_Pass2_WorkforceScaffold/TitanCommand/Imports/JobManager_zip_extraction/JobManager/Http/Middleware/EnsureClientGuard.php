<?php

namespace Modules\JobManager\Http\Middleware;

use Closure;


namespace ModulesJobManagerHttpMiddleware;

namespace Modules\JobManager\Http\Middleware;
use Closure;

class EnsureClientGuard {
  public function handle($request, Closure $next){
    if(!auth('client')->check()){ abort(403,'Client authentication required.'); }
    return $next($request);
  }
}
