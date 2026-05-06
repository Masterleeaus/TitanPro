<?php

namespace App\Services;

use App\Events\UsageLimitApproaching;
use App\Events\UsageLimitExceeded;
use App\Models\Organization;
use App\Models\TitanUsageMeter;
use Illuminate\Support\Carbon;

/**
 * Records usage events against a meter and fires billing events when
 * the usage approaches or exceeds the plan limit.
 */
class UsageMeterWriter
{
    public function __construct(private readonly PlanResolver $planResolver) {}

    /**
     * Increment the meter counter for the given organisation and meter key by $amount.
     *
     * Fires UsageLimitApproaching when usage reaches the approaching threshold
     * (default 80%) and UsageLimitExceeded when it reaches 100% of the limit.
     *
     * @return int The new count after incrementing.
     */
    public function increment(Organization $org, string $meterKey, int $amount = 1): int
    {
        $period  = now()->format('Y-m');
        $resetAt = now()->startOfMonth()->addMonth(); // first of next month

        /** @var TitanUsageMeter $meter */
        $meter = TitanUsageMeter::firstOrNew([
            'organization_id' => $org->id,
            'meter_key'       => $meterKey,
            'period'          => $period,
        ]);

        if (! $meter->exists) {
            $meter->count    = 0;
            $meter->reset_at = $resetAt;
        }

        $meter->count += $amount;
        $meter->save();

        $this->dispatchEvents($org, $meterKey, $meter->count);

        return $meter->count;
    }

    /**
     * Dispatch approaching / exceeded events as appropriate.
     */
    private function dispatchEvents(Organization $org, string $meterKey, int $count): void
    {
        $limit = $this->planResolver->limitFor($org, $meterKey);

        if ($limit === null || $limit === 0) {
            return; // unlimited or misconfigured — no events
        }

        $threshold = (float) config('billing.approaching_threshold', 0.8);

        if ($count >= $limit) {
            UsageLimitExceeded::dispatch($org, $meterKey, $count, $limit);
        } elseif ($threshold > 0 && $count >= (int) ceil($limit * $threshold)) {
            UsageLimitApproaching::dispatch($org, $meterKey, $count, $limit);
        }
    }
}
