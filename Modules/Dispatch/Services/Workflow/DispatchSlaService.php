<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Workflow;

use Illuminate\Support\Carbon;
use Modules\Dispatch\Models\DispatchSlaPolicy;
use Modules\Dispatch\Models\DispatchWorkOrder;

class DispatchSlaService
{
    public function policyFor(DispatchWorkOrder $workOrder): ?DispatchSlaPolicy
    {
        return DispatchSlaPolicy::query()
            ->where('active', true)
            ->where('priority', $workOrder->priority ?: 'normal')
            ->when($workOrder->company_id, fn ($query) => $query->where('company_id', $workOrder->company_id))
            ->orderByDesc('company_id')
            ->first();
    }

    public function dueAt(DispatchWorkOrder $workOrder): ?Carbon
    {
        $policy = $this->policyFor($workOrder);

        if (! $policy || ! $workOrder->scheduled_for) {
            return null;
        }

        return Carbon::parse($workOrder->scheduled_for)->addMinutes((int) $policy->completion_minutes);
    }

    public function isBreached(DispatchWorkOrder $workOrder): bool
    {
        $dueAt = $this->dueAt($workOrder);

        return $dueAt !== null
            && Carbon::now()->greaterThan($dueAt)
            && ! in_array($workOrder->status, ['completed', 'cancelled'], true);
    }
}
