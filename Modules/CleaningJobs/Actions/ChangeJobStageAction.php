<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobStageChanged;
use Modules\CleaningJobs\Models\WorkOrder;

class ChangeJobStageAction
{
    public function handle(WorkOrder $job, string $stage): WorkOrder
    {
        $job->update(['status' => $stage]);
        JobStageChanged::dispatch($job, 0, $stage);
        return $job;
    }
}
