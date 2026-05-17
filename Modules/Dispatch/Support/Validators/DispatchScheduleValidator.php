<?php

declare(strict_types=1);

namespace Modules\Dispatch\Support\Validators;

use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Modules\Dispatch\Models\DispatchWorkOrder;
use Modules\Dispatch\Services\Allocation\TechnicianAvailabilityService;
use Modules\Dispatch\Support\DTOs\ScheduleWindow;

final class DispatchScheduleValidator
{
    public function __construct(private readonly TechnicianAvailabilityService $availability) {}

    /**
     * @throws ValidationException
     */
    public function validate(DispatchWorkOrder $workOrder, int $technicianId, ScheduleWindow $window, array $options = []): void
    {
        $errors = [];

        if ($technicianId <= 0) {
            $errors['technician_id'][] = 'A valid technician is required.';
        }

        if (! $this->availability->isTechnicianAvailable($technicianId, $window, Arr::get($options, 'ignore_appointment_id'))) {
            $errors['starts_at'][] = 'Technician already has an overlapping dispatch appointment.';
        }

        if ($workOrder->status === 'cancelled') {
            $errors['work_order'][] = 'Cancelled work orders cannot be scheduled.';
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }
}
