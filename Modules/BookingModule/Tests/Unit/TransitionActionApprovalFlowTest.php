<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class TransitionActionApprovalFlowTest extends TestCase
{
    public function test_transition_action_contains_pending_approval_routing_logic(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Actions/Bookings/TransitionCleaningBookingAction.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString('$newStatus = \'pending_approval\';', $contents);
        $this->assertStringContainsString('BookingApprovalRequested', $contents);
        $this->assertStringContainsString('BookingApprovalDecided', $contents);
    }
}
