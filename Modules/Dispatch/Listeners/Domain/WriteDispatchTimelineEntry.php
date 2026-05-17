<?php

declare(strict_types=1);

namespace Modules\Dispatch\Listeners\Domain;

use Modules\Dispatch\Events\Domain\DispatchStatusChanged;
use Modules\Dispatch\Events\Domain\WorkOrderScheduled;
use Modules\Dispatch\Models\DispatchStatusLog;

class WriteDispatchTimelineEntry
{
    public function handle(object $event): void
    {
        if ($event instanceof WorkOrderScheduled) {
            DispatchStatusLog::query()->create([
                'company_id' => $event->workOrder->company_id,
                'work_order_id' => $event->workOrder->id,
                'appointment_id' => data_get($event->payload, 'appointment.id'),
                'assign_shift_id' => data_get($event->payload, 'assignment.id'),
                'from_status' => null,
                'to_status' => 'scheduled',
                'changed_at' => now(),
                'notes' => 'Work order scheduled from Dispatch.',
                'metadata' => ['source' => 'dispatch_scheduler'],
            ]);
        }

        if ($event instanceof DispatchStatusChanged) {
            DispatchStatusLog::query()->create([
                'company_id' => $event->assignment->company_id,
                'assign_shift_id' => $event->assignment->id,
                'work_order_id' => $event->assignment->work_order_id,
                'appointment_id' => $event->assignment->appointment_id,
                'from_status' => $event->fromStatus,
                'to_status' => $event->toStatus,
                'changed_at' => now(),
                'notes' => $event->notes,
                'metadata' => ['source' => 'dispatch_status_service'],
            ]);
        }
    }
}
