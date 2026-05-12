<?php

namespace Modules\BookingModule\Tests\Unit;

use Modules\BookingModule\Automation\BookingApprovalRuntime;
use Modules\BookingModule\Models\CleaningBooking;
use Tests\TestCase;

class ApprovalRuntimeThresholdTest extends TestCase
{
    public function test_high_value_booking_requires_approval(): void
    {
        config()->set('bookingmodule.automation.approval.high_value_threshold', 500.0);

        $runtime = new BookingApprovalRuntime();
        $booking = new CleaningBooking(['booking_value' => 650.00]);

        $this->assertTrue($runtime->requiresApproval($booking));
    }
}
