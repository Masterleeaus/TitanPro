<?php

namespace Modules\TitanEchoAssist\Billing\Limits;

use Illuminate\Support\Facades\Cache;

/**
 * Enforces per-company conversation usage caps for a billing period.
 *
 * All records are scoped by company_id to honour the tenancy boundary.
 */
class ConversationLimit
{
    private const DEFAULT_CAP    = 1000;
    private const COUNTER_PREFIX = 'billing_cap:conversations:';

    public function __construct(
        private readonly int $cap = self::DEFAULT_CAP,
    ) {}

    /**
     * Check whether a company has reached its conversation cap for the current
     * billing period.
     *
     * @param int    $companyId     Tenant boundary — always required.
     * @param string $billingPeriod "YYYY-MM" format; defaults to current month.
     */
    public function isExceeded(int $companyId, ?string $billingPeriod = null): bool
    {
        $period = $billingPeriod ?? date('Y-m');
        return $this->getUsage($companyId, $period) >= $this->cap;
    }

    /**
     * Increment the conversation counter for a company in the current billing period.
     */
    public function increment(int $companyId, ?string $billingPeriod = null): int
    {
        $key = $this->buildKey($companyId, $billingPeriod ?? date('Y-m'));
        return (int) Cache::increment($key);
    }

    /**
     * Get the current usage count for a company in a billing period.
     */
    public function getUsage(int $companyId, ?string $billingPeriod = null): int
    {
        $period = $billingPeriod ?? date('Y-m');
        return (int) Cache::get($this->buildKey($companyId, $period), 0);
    }

    /**
     * Reset the usage counter for a company (e.g., at billing period rollover).
     */
    public function reset(int $companyId, ?string $billingPeriod = null): void
    {
        $period = $billingPeriod ?? date('Y-m');
        Cache::forget($this->buildKey($companyId, $period));
    }

    public function getCap(): int
    {
        return $this->cap;
    }

    private function buildKey(int $companyId, string $billingPeriod): string
    {
        return self::COUNTER_PREFIX . "{$companyId}:{$billingPeriod}";
    }
}
