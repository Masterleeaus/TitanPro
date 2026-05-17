<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\TimesheetLogged;
use Modules\CleaningJobs\Models\WorkOrder;

class LogTimesheetAction
{
    public function handle(WorkOrder $job, float $hours): WorkOrder
    {
        $newHours = (float) ($job->actual_hours ?? 0) + $hours;
        $job->update(['actual_hours' => $newHours]);
        TimesheetLogged::dispatch($job, 0, $hours);
        return $job;
    }
}
