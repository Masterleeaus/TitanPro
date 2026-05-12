<?php

namespace Modules\GroundZeroOps\Actions;

use Modules\GroundZeroOps\Events\ShiftStarted;
use Modules\GroundZeroOps\Models\Shift;
use Modules\GroundZeroOps\Services\ShiftService;
use Modules\GroundZeroOps\Support\Scopes\ScopedByCompany;

class StartShiftAction
{
    public function __construct(private readonly ShiftService $shiftService) {}

    public function execute(int $technicianId, ?int $actorId = null, ?int $companyId = null): Shift
    {
        $resolvedCompanyId = $companyId ?? ScopedByCompany::resolveCompanyId();

        if ($resolvedCompanyId === null) {
            abort(403, 'Unable to resolve tenant context.');
        }

        $shift = $this->shiftService->start($resolvedCompanyId, $technicianId, $actorId);

        event(new ShiftStarted(
            companyId: $resolvedCompanyId,
            actorId: $actorId,
            sourceId: $shift->id,
            payload: [
                'shift_id' => $shift->id,
                'technician_id' => $shift->technician_id,
            ],
        ));

        return $shift;
    }
}
