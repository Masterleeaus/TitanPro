<?php

namespace Modules\CleaningJobs\Services;

use Modules\CleaningJobs\Models\WorkOrder;

class JobFinancialService
{
    public function syncWorkOrderFinancials(?WorkOrder $workOrder): void
    {
        if (! $workOrder) return;

        $actualCost = $workOrder->timesheets()->whereIn('status', ['submitted','approved'])->sum('cost_amount');
        $actualRevenue = $workOrder->timesheets()->whereIn('status', ['submitted','approved'])->sum('billable_amount');
        $actualHours = $workOrder->timesheets()->whereIn('status', ['submitted','approved'])->sum('hours');
        $estimatedHours = $workOrder->jobTasks()->sum('estimated_hours');

        $health = 'on_track';
        if ((float) $workOrder->budget_amount > 0 && $actualCost > (float) $workOrder->budget_amount) $health = 'over_budget';
        if ((float) $estimatedHours > 0 && $actualHours > $estimatedHours) $health = 'over_hours';

        $workOrder->forceFill([
            'actual_cost' => $actualCost,
            'actual_revenue' => $actualRevenue,
            'actual_hours' => $actualHours,
            'estimated_hours' => $estimatedHours,
            'health' => $health,
        ])->saveQuietly();
    }

    public function marginFor(WorkOrder $workOrder): float
    {
        return (float) $workOrder->actual_revenue - (float) $workOrder->actual_cost;
    }
}
