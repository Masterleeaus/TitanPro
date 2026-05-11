<?php

namespace Modules\BookingModule\Tests\Unit;

use Tests\TestCase;

class TransitionCleaningBookingActionTest extends TestCase
{
    public function test_cleaning_booking_controller_uses_transition_action_for_status_changes(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Http/Controllers/Cleaning/CleaningBookingController.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString('TransitionCleaningBookingAction', $contents);
        $this->assertStringContainsString('$this->transitionAction->execute($booking, $data[\'status\'])', $contents);
        $this->assertStringNotContainsString('new BookingCompleted(', $contents);
    }

    public function test_transition_action_emits_status_and_terminal_events(): void
    {
        $contents = file_get_contents(module_path('BookingModule', 'Actions/Bookings/TransitionCleaningBookingAction.php'));

        $this->assertNotFalse($contents);
        $this->assertStringContainsString('new BookingStatusChanged', $contents);
        $this->assertStringContainsString('new BookingCompleted', $contents);
        $this->assertStringContainsString('new BookingCancelled', $contents);
    }
}
