<?php

namespace Modules\CleaningJobs\Listeners;

use Modules\CleaningJobs\Events\WorkOrderCompleted;
use Modules\CleaningJobs\Models\WorkOrdersSetting;

class AutoConvertOnCompletion
{
    public function handle(WorkOrderCompleted $event): void
    {
        $settings = WorkOrdersSetting::getOrCreate();
        if ($settings->auto_convert_on_complete) {
            $event->workOrder->loadMissing('tasks');
            $event->workOrder->convertToProject();
        }
    }
}
