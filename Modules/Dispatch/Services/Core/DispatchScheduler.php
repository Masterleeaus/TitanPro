<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Core;

use Modules\Dispatch\Actions\Create\ScheduleDispatchWorkOrder;
use Modules\Dispatch\Contracts\Services\DispatchSchedulerContract;
use Modules\Dispatch\Events\Domain\WorkOrderScheduled;
use Modules\Dispatch\Models\DispatchWorkOrder;

class DispatchScheduler implements DispatchSchedulerContract
{
    public function __construct(private readonly ScheduleDispatchWorkOrder $scheduler) {}

    public function schedule(
        DispatchWorkOrder $workOrder,
        int $technicianId,
        string $startsAt,
        ?string $endsAt = null,
        ?int $shiftId = null,
        array $options = []
    ): DispatchWorkOrder {
        $scheduled = $this->scheduler->handle($workOrder, $technicianId, $startsAt, $endsAt, $shiftId, $options);

        event(new WorkOrderScheduled($scheduled->refresh(), ['work_order_id' => $scheduled->getKey()]));

        return $scheduled;
    }
}
