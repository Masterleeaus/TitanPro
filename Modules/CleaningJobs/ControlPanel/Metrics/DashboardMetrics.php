<?php

namespace Modules\CleaningJobs\ControlPanel\Metrics;

use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Workflows\Definitions\CleaningJobWorkflow;

class DashboardMetrics
{
    public function jobsToday(): int
    {
        return WorkOrder::query()
            ->whereDate('scheduled_for', today())
            ->count();
    }

    public function overdueJobs(): int
    {
        return WorkOrder::query()
            ->where(function ($query): void {
                $query->whereDate('due_by', '<', today())
                    ->orWhereDate('due_date', '<', today());
            })
            ->whereNotIn('status', CleaningJobWorkflow::terminalStatuses())
            ->count();
    }

    public function activeCleaners(): int
    {
        return WorkOrder::query()
            ->whereDate('scheduled_for', today())
            ->whereNotNull('technician_id')
            ->whereIn('status', [
                CleaningJobWorkflow::STATUS_OPEN,
                CleaningJobWorkflow::STATUS_SCHEDULED,
                CleaningJobWorkflow::STATUS_IN_PROGRESS,
            ])
            ->distinct('technician_id')
            ->count('technician_id');
    }
}
