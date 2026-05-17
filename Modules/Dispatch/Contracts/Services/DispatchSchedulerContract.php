<?php

declare(strict_types=1);

namespace Modules\Dispatch\Contracts\Services;

use Modules\Dispatch\Models\DispatchWorkOrder;

interface DispatchSchedulerContract
{
    /**
     * Schedule a work order and return the created appointment/assignment payload.
     *
     * @param array<string,mixed> $options
     * @return DispatchWorkOrder
     */
    public function schedule(
        DispatchWorkOrder $workOrder,
        int $technicianId,
        string $startsAt,
        ?string $endsAt = null,
        ?int $shiftId = null,
        array $options = []
    ): DispatchWorkOrder;
}
