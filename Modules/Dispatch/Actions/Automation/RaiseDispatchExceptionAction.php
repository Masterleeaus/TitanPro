<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Automation;

use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Models\DispatchException;
use Modules\Dispatch\Models\DispatchWorkOrder;

class RaiseDispatchExceptionAction
{
    public function handle(
        string $type,
        string $message,
        ?DispatchWorkOrder $workOrder = null,
        ?DispatchAppointment $appointment = null,
        string $severity = 'medium',
        array $metadata = []
    ): DispatchException {
        return DispatchException::create([
            'company_id' => $workOrder?->company_id ?? $appointment?->company_id,
            'work_order_id' => $workOrder?->getKey() ?? $appointment?->work_order_id,
            'appointment_id' => $appointment?->getKey(),
            'technician_id' => $appointment?->technician_id ?? $workOrder?->technician_id,
            'type' => $type,
            'severity' => $severity,
            'status' => 'open',
            'message' => $message,
            'metadata' => $metadata,
        ]);
    }
}
