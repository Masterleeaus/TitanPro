<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobPriorityChanged;
use Modules\CleaningJobs\Models\WorkOrder;

class ChangeJobPriorityAction
{
    public function handle(WorkOrder $job, string $priority): WorkOrder
    {
        $job->update(['priority' => $priority]);
        JobPriorityChanged::dispatch($job, 0, $priority);
        return $job;
    }
}
