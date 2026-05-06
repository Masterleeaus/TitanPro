<?php

namespace Modules\CleaningJobs\ControlPanel\Metrics;

use Illuminate\Support\Collection;

/**
 * DashboardMetrics acts as a placeholder for the metrics strip in the TitanWork control panel.
 *
 * Each method should return a numeric value or small collection to be rendered
 * in the top row of the control panel UI.
 */
class DashboardMetrics
{
    /**
     * Example metric: number of jobs scheduled for today.
     * Replace with real implementation by querying the host system's jobs table.
     */
    public function jobsToday(): int
    {
        // TODO: integrate with system jobs data to count today's jobs
        return 0;
    }

    /**
     * Example metric: number of overdue jobs.
     */
    public function overdueJobs(): int
    {
        // TODO: compute overdue jobs count
        return 0;
    }

    /**
     * Example metric: active cleaners on shift.
     */
    public function activeCleaners(): int
    {
        // TODO: compute active cleaners count
        return 0;
    }
}