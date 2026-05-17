<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Update;

use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Support\Enums\CleaningVisitStatus;

class CheckInCleaningVisitAction
{
    public function execute(DispatchAppointment $appointment, array $payload = []): DispatchAppointment
    {
        $metadata = $appointment->metadata ?? [];
        $metadata['check_in'] = array_filter([
            'at' => now()->toIso8601String(),
            'latitude' => $payload['latitude'] ?? null,
            'longitude' => $payload['longitude'] ?? null,
            'notes' => $payload['notes'] ?? null,
        ], fn ($value) => $value !== null);

        $appointment->forceFill([
            'status' => CleaningVisitStatus::CheckedIn->value,
            'metadata' => $metadata,
        ])->save();

        $appointment->workOrder?->forceFill([
            'status' => CleaningVisitStatus::InProgress->value,
            'started_at' => $appointment->workOrder->started_at ?? now(),
        ])->save();

        return $appointment->refresh();
    }
}
