<?php

namespace Modules\BookingModule\Tests\Feature;

use Modules\BookingModule\Models\CleaningBooking;
use Tests\TestCase;

class BookingCancelPathsTest extends TestCase
{
    public function test_cancellation_is_available_from_each_non_terminal_state(): void
    {
        foreach (['draft', 'pending_approval', 'confirmed', 'dispatched', 'in_progress', 'completed', 'invoiced', 'rescheduled', 'no_show'] as $status) {
            $booking = new CleaningBooking(['booking_status' => $status]);
            $this->assertTrue($booking->canTransitionTo('cancelled'), "Expected {$status} to support cancellation.");
        }
    }
}

