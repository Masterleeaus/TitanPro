<?php

namespace Modules\GroundZeroOps\Services;

use Modules\GroundZeroOps\Models\Shift;

class ShiftService
{
    public function start(int $companyId, int $technicianId, ?int $actorId = null): Shift
    {
        return Shift::query()->create([
            'company_id' => $companyId,
            'technician_id' => $technicianId,
            'status' => 'active',
            'started_at' => now(),
            'started_by' => $actorId,
        ]);
    }

    public function end(Shift $shift, ?int $actorId = null): Shift
    {
        $shift->forceFill([
            'status' => 'ended',
            'ended_at' => now(),
            'ended_by' => $actorId,
        ])->save();

        return $shift->refresh();
    }
}
