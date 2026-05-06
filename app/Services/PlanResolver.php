<?php

namespace App\Services;

use App\Models\Organization;

/**
 * Resolves the active billing plan for an organisation.
 *
 * Thin wrapper around PlanService::activePlan() so that other billing
 * components only depend on PlanResolver, not the full PlanService.
 */
class PlanResolver
{
    public function __construct(private readonly PlanService $planService) {}

    /**
     * Returns the active plan key for the given organisation.
     *
     * During a trial, Starter orgs are bumped to Growth features.
     * Returns 'starter' (most restrictive) when no subscription exists.
     */
    public function resolve(Organization $org): string
    {
        return $this->planService->activePlan($org);
    }

    /**
     * The numeric limit for a given meter key on the organisation's active plan.
     * Returns null when the plan grants unlimited usage.
     */
    public function limitFor(Organization $org, string $meterKey): ?int
    {
        $plan   = $this->resolve($org);
        $limits = config("billing.meters.{$meterKey}.limits", []);

        if (! array_key_exists($plan, $limits)) {
            return null; // unknown meter key or plan → treat as unlimited
        }

        return $limits[$plan]; // may be null (unlimited) or an integer
    }
}
