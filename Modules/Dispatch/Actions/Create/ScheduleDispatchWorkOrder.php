<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Create;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Dispatch\Models\AssignShift;
use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Models\DispatchWorkOrder;
use Modules\Dispatch\Models\Shift;
use Modules\Dispatch\Support\DTOs\ScheduleWindow;
use Modules\Dispatch\Support\Validators\DispatchScheduleValidator;

class ScheduleDispatchWorkOrder
{
    public function __construct(private readonly DispatchScheduleValidator $validator) {}

    public function handle(
        DispatchWorkOrder $workOrder,
        int $technicianId,
        CarbonInterface|string $startsAt,
        CarbonInterface|string|null $endsAt = null,
        ?int $shiftId = null,
        array $attributes = []
    ): DispatchWorkOrder {
        $starts = $startsAt instanceof CarbonInterface ? Carbon::instance($startsAt) : Carbon::parse($startsAt);
        $durationHours = (float) ($workOrder->estimated_hours ?: ($attributes['estimated_hours'] ?? 2));
        $ends = $endsAt instanceof CarbonInterface
            ? Carbon::instance($endsAt)
            : ($endsAt ? Carbon::parse($endsAt) : $starts->copy()->addMinutes((int) max(30, $durationHours * 60)));

        $window = new ScheduleWindow($starts->toImmutable(), $ends->toImmutable());
        $this->validator->validate($workOrder, $technicianId, $window, $attributes);

        return DB::transaction(function () use ($workOrder, $technicianId, $starts, $ends, $shiftId, $attributes): DispatchWorkOrder {
            $shift = $shiftId ? Shift::query()->find($shiftId) : null;

            $appointment = DispatchAppointment::query()->updateOrCreate(
                ['work_order_id' => $workOrder->id],
                [
                    'company_id' => $workOrder->company_id,
                    'technician_id' => $technicianId,
                    'shift_id' => $shift?->id,
                    'customer_location_id' => $attributes['customer_location_id'] ?? $workOrder->customer_location_id,
                    'starts_at' => $starts,
                    'ends_at' => $ends,
                    'start_date' => $starts->toDateString(),
                    'start_time' => $starts->format('H:i:s'),
                    'end_date' => $ends->toDateString(),
                    'end_time' => $ends->format('H:i:s'),
                    'location' => $attributes['location'] ?? $workOrder->location,
                    'status' => $attributes['status'] ?? 'scheduled',
                    'notes' => $attributes['notes'] ?? $workOrder->notes,
                    'metadata' => $attributes['metadata'] ?? [],
                ]
            );

            AssignShift::query()->updateOrCreate(
                ['work_order_id' => $workOrder->id, 'appointment_id' => $appointment->id],
                [
                    'company_id' => $workOrder->company_id,
                    'shift_id' => $shift?->id,
                    'employee_id' => $technicianId,
                    'publish' => 1,
                    'date_added' => $starts->toDateString(),
                    'month_added' => $starts->format('m'),
                    'year_added' => $starts->format('Y'),
                    'dispatch_status' => $appointment->status,
                    'dispatch_notes' => $attributes['dispatch_notes'] ?? null,
                    'color' => $attributes['color'] ?? config('dispatch.default_shift_color', '#2563eb'),
                ]
            );

            $workOrder->forceFill([
                'technician_id' => $technicianId,
                'scheduled_for' => $starts,
                'status' => $attributes['work_order_status'] ?? 'scheduled',
            ])->save();

            return $workOrder->refresh()->load(['appointments', 'primaryShiftAssignment.shift']);
        });
    }
}
