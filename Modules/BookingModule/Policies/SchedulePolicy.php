<?php

namespace Modules\BookingModule\Policies;

use App\Models\User;
use Modules\BookingModule\Entities\Schedule;

class SchedulePolicy
{
    public function manage(User $user): bool
    {
        return \Modules\BookingModule\Support\AppointmentPermission::check($user, 'schedule manage');
    }

    public function update(User $user, Schedule $schedule): bool
    {
        return $this->isSameTenant($user, $schedule)
            && (
                \Modules\BookingModule\Support\AppointmentPermission::check($user, 'appointment dispatch')
                || \Modules\BookingModule\Support\AppointmentPermission::check($user, 'schedule manage')
            );
    }

    protected function isSameTenant(User $user, Schedule $schedule): bool
    {
        $userTenantId = (int) ($user->company_id ?? $user->organization_id ?? 0);
        $scheduleTenantId = (int) ($schedule->company_id ?? 0);

        if ($userTenantId <= 0 || $scheduleTenantId <= 0) {
            return false;
        }

        return $userTenantId === $scheduleTenantId;
    }
}
