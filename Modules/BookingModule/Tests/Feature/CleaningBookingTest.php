<?php

namespace Modules\BookingModule\Tests\Feature;

use Illuminate\Validation\ValidationException;
use Modules\BookingModule\Models\CleaningBooking;
use Modules\BookingModule\Services\BookingFSMService;
use Tests\TestCase;

class CleaningBookingTest extends TestCase
{
    public function test_draft_can_move_to_confirmed(): void
    {
        $booking = new CleaningBooking(['booking_status' => 'draft']);

        $this->assertTrue($booking->canTransitionTo('confirmed'));
    }

    public function test_confirmed_cannot_skip_to_completed(): void
    {
        $booking = new CleaningBooking(['booking_status' => 'confirmed']);

        $this->assertFalse($booking->canTransitionTo('completed'));
    }

    public function test_fsm_blocks_confirmed_to_dispatched_without_technician_assignment(): void
    {
        $service = new BookingFSMService();
        $booking = new class(['booking_status' => 'confirmed']) extends CleaningBooking {
            public function save(array $options = []): bool
            {
                return true;
            }
        };

        $this->expectException(ValidationException::class);
        $service->transition($booking, 'dispatched');
    }

    public function test_fsm_blocks_completed_to_invoiced_without_job_card_signal(): void
    {
        $service = new BookingFSMService();
        $booking = new class(['booking_status' => 'completed']) extends CleaningBooking {
            public function save(array $options = []): bool
            {
                return true;
            }
        };

        $this->expectException(ValidationException::class);
        $service->transition($booking, 'invoiced');
    }

    public function test_fsm_accepts_completed_to_invoiced_with_job_card_signal_context(): void
    {
        $service = new BookingFSMService();
        $booking = new class(['booking_status' => 'completed']) extends CleaningBooking {
            public bool $saved = false;

            public function save(array $options = []): bool
            {
                $this->saved = true;

                return true;
            }
        };

        $result = $service->transition($booking, 'invoiced', ['job_card_completed' => true]);

        $this->assertSame('invoiced', $result->booking_status);
        $this->assertTrue($booking->saved);
    }
}

