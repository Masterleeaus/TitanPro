<?php

namespace App\Extensions\TitanCommand\System\JobManager\Listeners;

use App\Extensions\TitanCommand\System\JobManager\Entities\JobManagerSetting;
use App\Extensions\TitanCommand\System\JobManager\Events\WorkOrderCompleted;






class AutoConvertOnCompletion
{
    public function handle(WorkOrderCompleted $event): void
    {
        $settings = JobManagerSetting::getOrCreate();
        if ($settings->auto_convert_on_complete) {
            $event->workOrder->loadMissing('tasks');
            $event->workOrder->convertToProject();
        }
    }
}
