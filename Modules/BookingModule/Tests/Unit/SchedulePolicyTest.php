<?php

namespace Modules\BookingModule\Tests\Unit;

use App\Models\User;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Policies\SchedulePolicy;
use Tests\TestCase;

class SchedulePolicyTest extends TestCase
{
    public function test_update_denies_cross_tenant_schedule_access(): void
    {
        $policy = new SchedulePolicy();

        $user = new class extends User {
            public function permission($permission): bool
            {
                return true;
            }

            public function can($ability, $arguments = []): bool
            {
                return true;
            }
        };
        $user->forceFill(['company_id' => 10]);

        $schedule = new Schedule();
        $schedule->forceFill(['company_id' => 11]);

        $this->assertFalse($policy->update($user, $schedule));
    }
}
