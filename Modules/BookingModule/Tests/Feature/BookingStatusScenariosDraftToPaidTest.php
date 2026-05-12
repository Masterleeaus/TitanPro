<?php

namespace Modules\BookingModule\Tests\Feature;

use Modules\BookingModule\Models\CleaningBooking;
use Tests\TestCase;

class BookingStatusScenariosDraftToPaidTest extends TestCase
{
    public function test_draft_to_paid_happy_path_state_progression_is_declared(): void
    {
        $booking = new CleaningBooking(['booking_status' => 'draft']);

        $this->assertTrue($booking->canTransitionTo('confirmed'));

        $booking->booking_status = 'confirmed';
        $this->assertTrue($booking->canTransitionTo('dispatched'));

        $booking->booking_status = 'dispatched';
        $this->assertTrue($booking->canTransitionTo('in_progress'));

        $booking->booking_status = 'in_progress';
        $this->assertTrue($booking->canTransitionTo('completed'));

        $booking->booking_status = 'completed';
        $this->assertTrue($booking->canTransitionTo('invoiced'));

        $booking->booking_status = 'invoiced';
        $this->assertTrue($booking->canTransitionTo('paid'));
    }
}

