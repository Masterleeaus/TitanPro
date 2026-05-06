<?php

namespace App\Http\Middleware;

use App\Models\TitanUsageMeter;
use App\Services\PlanResolver;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Blocks a request with HTTP 402 when the organisation has exhausted its
 * plan limit for the given meter key.
 *
 * Usage:
 *   Route::post('/jobs', ...)->middleware('titan.billing.limit:cleaning_jobs');
 */
class LimitCheckerMiddleware
{
    public function __construct(private readonly PlanResolver $planResolver) {}

    public function handle(Request $request, Closure $next, string $meterKey): Response
    {
        $user = $request->user();

        if (! $user || ! $user->organization_id) {
            return $next($request);
        }

        $org   = $user->organization;
        $limit = $this->planResolver->limitFor($org, $meterKey);

        if ($limit !== null) {
            $count = TitanUsageMeter::countFor($org->id, $meterKey);

            if ($count >= $limit) {
                $plan = $this->planResolver->resolve($org);

                $message = "Your {$plan} plan allows up to {$limit} {$meterKey}. "
                    .'Upgrade your plan to continue.';

                if ($request->expectsJson()) {
                    return response()->json([
                        'message'    => $message,
                        'meter_key'  => $meterKey,
                        'limit'      => $limit,
                        'count'      => $count,
                    ], Response::HTTP_PAYMENT_REQUIRED);
                }

                return back()->withErrors(['billing_limit' => $message]);
            }
        }

        return $next($request);
    }
}
