<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobCancelled;
use Modules\CleaningJobs\Models\WorkOrder;

class CancelJobAction
{
    public function handle(WorkOrder $job, string $reason = ''): WorkOrder
    {
        $job->update(['status' => 'cancelled']);
        JobCancelled::dispatch($job, 0, $reason);
        return $job;
    }
}
