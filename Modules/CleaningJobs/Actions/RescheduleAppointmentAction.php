<?php

namespace Modules\CleaningJobs\Actions;

use DateTimeInterface;
use Modules\CleaningJobs\Events\AppointmentRescheduled;
use Modules\CleaningJobs\Models\WorkOrder;

class RescheduleAppointmentAction
{
    public function execute(WorkOrder $workOrder, DateTimeInterface $newDate): WorkOrder
    {
        event(new AppointmentRescheduled($workOrder, $newDate));
        return $workOrder;
    }
}
