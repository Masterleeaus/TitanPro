<?php

namespace Modules\CleaningJobs\Actions;

use Modules\CleaningJobs\Events\AppointmentConfirmed;
use Modules\CleaningJobs\Models\WorkOrder;

class ConfirmAppointmentAction
{
    public function execute(WorkOrder $workOrder): WorkOrder
    {
        event(new AppointmentConfirmed($workOrder));
        return $workOrder;
    }
}
