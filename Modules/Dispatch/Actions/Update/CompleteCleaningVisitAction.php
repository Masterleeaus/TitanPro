<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Update;

use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Support\Enums\CleaningVisitStatus;

class CompleteCleaningVisitAction
{
    public function execute(DispatchAppointment $appointment, array $payload = []): DispatchAppointment
    {
        $metadata = $appointment->metadata ?? [];
        $metadata['completion'] = array_filter([
            'at' => now()->toIso8601String(),
            'notes' => $payload['notes'] ?? null,
            'before_photos' => $payload['before_photos'] ?? [],
            'after_photos' => $payload['after_photos'] ?? [],
            'customer_signature' => $payload['customer_signature'] ?? null,
        ], fn ($value) => $value !== null && $value !== []);

        $appointment->forceFill([
            'status' => CleaningVisitStatus::Completed->value,
            'metadata' => $metadata,
        ])->save();

        $appointment->workOrder?->forceFill([
            'status' => CleaningVisitStatus::Completed->value,
            'completed_at' => now(),
        ])->save();

        return $appointment->refresh();
    }
}
