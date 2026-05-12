<?php

namespace Modules\GroundZeroOps\Actions;

use Modules\GroundZeroOps\Events\ShiftEnded;
use Modules\GroundZeroOps\Models\Shift;
use Modules\GroundZeroOps\Services\ShiftService;

class EndShiftAction
{
    public function __construct(private readonly ShiftService $shiftService) {}

    public function execute(Shift $shift, ?int $actorId = null): Shift
    {
        $shift = $this->shiftService->end($shift, $actorId);

        event(new ShiftEnded(
            companyId: (int) $shift->company_id,
            actorId: $actorId,
            sourceId: $shift->id,
            payload: [
                'shift_id' => $shift->id,
                'technician_id' => $shift->technician_id,
                'status' => $shift->status,
            ],
        ));

        return $shift;
    }
}
