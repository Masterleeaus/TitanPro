<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class TransitionActionSignalFlowTest extends TestCase
{
    public function test_transition_action_keeps_status_completed_and_cancelled_events(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Actions/Bookings/TransitionCleaningBookingAction.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString('new BookingStatusChanged', $contents);
        $this->assertStringContainsString('new BookingCompleted', $contents);
        $this->assertStringContainsString('new BookingCancelled', $contents);
    }
}

