<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Notifications;

use Illuminate\Support\Facades\Notification;
use Modules\Dispatch\Models\DispatchWorkOrder;
use Modules\Dispatch\Notifications\InApp\DispatchAssignmentUpdatedNotification;

class DispatchNotificationRouter
{
    public function notifyTechnicianAssigned(DispatchWorkOrder $workOrder): void
    {
        $technician = $workOrder->technician;

        if (! $technician) {
            return;
        }

        $assignment = $workOrder->primaryShiftAssignment;

        if (! $assignment) {
            return;
        }

        Notification::send($technician, new DispatchAssignmentUpdatedNotification($assignment));
    }
}
