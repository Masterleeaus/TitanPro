<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Cleaning;

use Illuminate\Support\Carbon;
use Modules\Dispatch\Models\DispatchAppointment;

class CleanerWorkloadService
{
    public function dailySummary(int $cleanerId, Carbon|string|null $date = null): array
    {
        $day = $date instanceof Carbon ? $date : Carbon::parse($date ?? 'today');
        $appointments = DispatchAppointment::query()
            ->where('technician_id', $cleanerId)
            ->whereDate('starts_at', $day->toDateString())
            ->orderBy('starts_at')
            ->get();

        $minutes = $appointments->sum(fn (DispatchAppointment $appointment): int => $appointment->starts_at && $appointment->ends_at
            ? max(0, $appointment->starts_at->diffInMinutes($appointment->ends_at))
            : 0);

        return [
            'cleaner_id' => $cleanerId,
            'date' => $day->toDateString(),
            'visits' => $appointments->count(),
            'scheduled_minutes' => $minutes,
            'scheduled_hours' => round($minutes / 60, 2),
            'first_visit_at' => optional($appointments->first())->starts_at?->toIso8601String(),
            'last_visit_at' => optional($appointments->last())->ends_at?->toIso8601String(),
        ];
    }
}
