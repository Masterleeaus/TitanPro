<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Update;

use Illuminate\Support\Arr;
use Modules\Dispatch\Events\Domain\WorkOrderScheduled;
use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Support\DTOs\ScheduleWindow;
use Modules\Dispatch\Support\Validators\DispatchScheduleValidator;

class RescheduleDispatchAppointmentAction
{
    public function __construct(private readonly DispatchScheduleValidator $validator) {}

    public function handle(DispatchAppointment $appointment, string $startsAt, ?string $endsAt = null, array $options = []): DispatchAppointment
    {
        $workOrder = $appointment->workOrder;
        $technicianId = (int) Arr::get($options, 'technician_id', $appointment->technician_id);
        $window = ScheduleWindow::fromStrings($startsAt, $endsAt, $workOrder?->estimated_hours ?: 1);

        if ($workOrder) {
            $this->validator->validate($workOrder, $technicianId, $window, ['ignore_appointment_id' => $appointment->getKey()]);
        }

        $appointment->forceFill([
            'technician_id' => $technicianId,
            'starts_at' => $window->startsAt,
            'ends_at' => $window->endsAt,
            'start_date' => $window->startsAt->toDateString(),
            'start_time' => $window->startsAt->format('H:i:s'),
            'end_date' => $window->endsAt->toDateString(),
            'end_time' => $window->endsAt->format('H:i:s'),
            'status' => Arr::get($options, 'status', 'scheduled'),
        ])->save();

        if ($workOrder) {
            $workOrder->forceFill([
                'technician_id' => $technicianId,
                'scheduled_for' => $window->startsAt,
                'status' => 'scheduled',
            ])->save();

            event(new WorkOrderScheduled($workOrder->refresh(), ['appointment' => $appointment->fresh()?->toArray()]));
        }

        return $appointment->refresh();
    }
}
