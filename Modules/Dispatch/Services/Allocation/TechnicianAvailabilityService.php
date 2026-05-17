<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Allocation;

use Illuminate\Database\Eloquent\Builder;
use Modules\Dispatch\Models\DispatchAppointment;
use Modules\Dispatch\Support\DTOs\ScheduleWindow;

class TechnicianAvailabilityService
{
    public function isTechnicianAvailable(int $technicianId, ScheduleWindow $window, ?int $ignoreAppointmentId = null): bool
    {
        return ! $this->overlappingAppointments($technicianId, $window, $ignoreAppointmentId)->exists();
    }

    public function overlappingAppointments(int $technicianId, ScheduleWindow $window, ?int $ignoreAppointmentId = null): Builder
    {
        return DispatchAppointment::query()
            ->where('technician_id', $technicianId)
            ->when($ignoreAppointmentId, fn (Builder $query) => $query->whereKeyNot($ignoreAppointmentId))
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->where(function (Builder $query) use ($window): void {
                $query->where('starts_at', '<', $window->endsAt)
                    ->where('ends_at', '>', $window->startsAt);
            });
    }

    public function dailyLoadMinutes(int $technicianId, string $date): int
    {
        return (int) DispatchAppointment::query()
            ->where('technician_id', $technicianId)
            ->whereDate('starts_at', $date)
            ->whereNotIn('status', ['cancelled'])
            ->get()
            ->sum(fn (DispatchAppointment $appointment): int => $appointment->starts_at && $appointment->ends_at
                ? $appointment->starts_at->diffInMinutes($appointment->ends_at)
                : 0);
    }
}
