<?php

namespace Modules\JobManager\Listeners;

use Modules\JobManager\Entities\JobManagerSetting;
use Modules\JobManager\Events\WorkOrderCompleted;


namespace ModulesJobManagerListeners;


namespace Modules\JobManager\Listeners;

use Modules\JobManager\Events\WorkOrderCompleted;
use Modules\JobManager\Entities\JobManagerSetting;

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
