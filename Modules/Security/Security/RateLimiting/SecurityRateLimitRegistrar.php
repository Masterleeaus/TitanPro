<?php

namespace Modules\Security\Security\RateLimiting;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class SecurityRateLimitRegistrar
{
    public function register(): void
    {
        RateLimiter::for('security-api', function (Request $request) {
            $userKey = optional($request->user())->id ?: $request->ip();
            return Limit::perMinute((int) config('security.rate_limits.api_per_minute', 60))->by('security-api:' . $userKey);
        });

        RateLimiter::for('security-upload', function (Request $request) {
            $userKey = optional($request->user())->id ?: $request->ip();
            return Limit::perMinute((int) config('security.rate_limits.uploads_per_minute', 10))->by('security-upload:' . $userKey);
        });
    }
}
