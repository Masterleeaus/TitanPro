<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\JobCreated;
use Modules\CleaningJobs\Models\WorkOrder;

class CreateJobAction
{
    public function handle(array $data): WorkOrder
    {
        $job = WorkOrder::create($data);
        JobCreated::dispatch($job);
        return $job;
    }
}
