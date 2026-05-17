<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Cleaning;

use Illuminate\Support\Carbon;
use Modules\Dispatch\Models\DispatchAppointment;

class CleanerReminderService
{
    public function dueAppointments(int $minutesAhead = 60): array
    {
        $from = now();
        $to = now()->addMinutes($minutesAhead);

        return DispatchAppointment::query()
            ->with(['workOrder', 'technician'])
            ->whereBetween('starts_at', [$from, $to])
            ->whereNotIn('status', ['completed', 'cancelled', 'missed'])
            ->orderBy('starts_at')
            ->get()
            ->map(fn (DispatchAppointment $appointment): array => [
                'appointment_id' => $appointment->id,
                'cleaner_id' => $appointment->technician_id,
                'starts_at' => $appointment->starts_at?->toIso8601String(),
                'title' => $appointment->workOrder?->title ?? 'Cleaning visit',
                'location' => $appointment->location ?? $appointment->workOrder?->location,
            ])
            ->all();
    }
}
