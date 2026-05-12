<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobRescheduled;
use Modules\CleaningJobs\Models\WorkOrder;

class RescheduleJobAction
{
    public function handle(WorkOrder $job, string $newDate): WorkOrder
    {
        $job->update(['scheduled_for' => $newDate]);
        JobRescheduled::dispatch($job, 0, $newDate);
        return $job;
    }
}
