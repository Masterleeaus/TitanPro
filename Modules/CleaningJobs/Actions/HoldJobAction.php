<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobOnHold;
use Modules\CleaningJobs\Models\WorkOrder;

class HoldJobAction
{
    public function handle(WorkOrder $job): WorkOrder
    {
        $job->update(['status' => 'on_hold']);
        JobOnHold::dispatch($job);
        return $job;
    }
}
