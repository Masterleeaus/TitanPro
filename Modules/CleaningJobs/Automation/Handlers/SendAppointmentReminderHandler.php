<?php

namespace Modules\CleaningJobs\Automation\Handlers;

use Modules\CleaningJobs\Jobs\DispatchCleaningReminder;
use Modules\CleaningJobs\Models\WorkOrder;

class SendAppointmentReminderHandler
{
    public function handle(WorkOrder $order): void
    {
        DispatchCleaningReminder::dispatch($order->id);
    }
}
