<?php

namespace Modules\BookingModule\Tests\Feature;

use Modules\BookingModule\Models\CleaningBooking;
use Tests\TestCase;

class BookingRescheduleAndNoShowTest extends TestCase
{
    public function test_reschedule_and_no_show_paths_are_declared_in_state_machine(): void
    {
        $confirmed = new CleaningBooking(['booking_status' => 'confirmed']);
        $dispatched = new CleaningBooking(['booking_status' => 'dispatched']);
        $noShow = new CleaningBooking(['booking_status' => 'no_show']);

        $this->assertTrue($confirmed->canTransitionTo('rescheduled'));
        $this->assertTrue($confirmed->canTransitionTo('no_show'));
        $this->assertTrue($dispatched->canTransitionTo('rescheduled'));
        $this->assertTrue($dispatched->canTransitionTo('no_show'));
        $this->assertTrue($noShow->canTransitionTo('rescheduled'));
    }
}
