<?php

namespace Modules\BookingModule\Tests\Feature;

use Tests\TestCase;

class BookingApprovalTimeoutActionTest extends TestCase
{
    public function test_timeout_action_denies_expired_pending_approvals(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Actions/Bookings/ExpirePendingBookingApprovalsAction.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString("where('booking_status', 'pending_approval')", $contents);
        $this->assertStringContainsString("'approval_timeout' => true", $contents);
    }
}

