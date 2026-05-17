<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Update;

use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Support\Enums\CleaningVisitStatus;

class MarkCleaningVisitMissedAction
{
    public function execute(DispatchAppointment $appointment, ?string $reason = null): DispatchAppointment
    {
        $metadata = $appointment->metadata ?? [];
        $metadata['missed'] = [
            'at' => now()->toIso8601String(),
            'reason' => $reason,
        ];

        $appointment->forceFill([
            'status' => CleaningVisitStatus::Missed->value,
            'metadata' => $metadata,
        ])->save();

        $appointment->workOrder?->forceFill(['status' => CleaningVisitStatus::Missed->value])->save();

        return $appointment->refresh();
    }
}
