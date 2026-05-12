<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobCompleted;
use Modules\CleaningJobs\Models\WorkOrder;

class CompleteJobAction
{
    public function handle(WorkOrder $job): WorkOrder
    {
        $job->update(['status' => 'completed']);
        JobCompleted::dispatch($job);
        return $job;
    }
}
