<?php

namespace Modules\TitanNexus\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyTitanVoiceSignature
{
    public function handle(Request $request, Closure $next)
    {
        // Adapter slot for imported VerifyTwilioSignature logic.
        // Kept permissive until account secrets are configured in Config/ai.php and Config/config.php.
        return $next($request);
    }
}
