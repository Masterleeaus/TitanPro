<?php

namespace Modules\BookingModule\Policies;

use App\Models\User;
use Modules\BookingModule\Entities\Appointment;

class AppointmentPolicy
{
    public function assign(User $user, Appointment $appointment): bool
    {
        $userTenantId = $user->company_id ?? $user->organization_id;
        if ((int) ($appointment->company_id ?? 0) !== (int) ($userTenantId ?? 0)) {
            return false;
        }

        return \Modules\BookingModule\Support\AppointmentPermission::check($user, 'appointments assign');
    }
}
