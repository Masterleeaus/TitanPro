<?php
namespace Modules\CleaningJobs\Actions;
use Modules\CleaningJobs\Models\JobTimesheet;
use Modules\CleaningJobs\Enums\TimesheetStatus;
class StartJobTimesheet
{
    public function execute(int $workOrderId, int $userId, ?string $startedAt = null): JobTimesheet
    {
        return JobTimesheet::create(['work_order_id'=>$workOrderId,'user_id'=>$userId,'started_at'=>$startedAt ?: now(),'status'=>TimesheetStatus::RUNNING->value]);
    }
}
