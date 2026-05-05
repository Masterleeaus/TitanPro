<?php

namespace Modules\CleaningJobs\Automation\Pipelines;

use Modules\CleaningJobs\Automation\Handlers\SendAppointmentReminderHandler;
use Modules\CleaningJobs\Automation\Triggers\JobScheduledTrigger;
use Modules\CleaningJobs\Models\WorkOrder;

class JobAutomationPipeline
{
    public function __construct(private JobScheduledTrigger $trigger, private SendAppointmentReminderHandler $handler) {}

    public function run(WorkOrder $order): bool
    {
        if (! $this->trigger->shouldRun($order)) {
            return false;
        }
        $this->handler->handle($order);
        return true;
    }
}
