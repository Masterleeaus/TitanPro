<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobStarted;
use Modules\CleaningJobs\Models\WorkOrder;

class StartJobAction
{
    public function handle(WorkOrder $job): WorkOrder
    {
        $job->update(['status' => 'in_progress']);
        JobStarted::dispatch($job);
        return $job;
    }
}
