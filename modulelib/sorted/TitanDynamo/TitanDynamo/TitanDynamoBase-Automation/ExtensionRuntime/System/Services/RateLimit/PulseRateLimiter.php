<?php
namespace App\Extensions\TitanPulse\System\Services\RateLimit;
use Illuminate\Support\Facades\RateLimiter;
class PulseRateLimiter {
    public function tooManyAttempts(string $key, int $maxAttempts=5, int $decaySeconds=300): bool {
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) return true;
        RateLimiter::hit($key, $decaySeconds);
        return false;
    }
}
