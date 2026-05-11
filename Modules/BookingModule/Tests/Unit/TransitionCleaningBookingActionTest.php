<?php

namespace Modules\BookingModule\Tests\Unit;

use Illuminate\Support\Facades\Event;
use Mockery;
use Modules\BookingModule\Actions\Bookings\TransitionCleaningBookingAction;
use Modules\BookingModule\Events\BookingCancelled;
use Modules\BookingModule\Events\BookingCompleted;
use Modules\BookingModule\Events\BookingStatusChanged;
use Modules\BookingModule\Models\CleaningBooking;
use Modules\BookingModule\Services\BookingFSMService;
use Tests\TestCase;

class TransitionCleaningBookingActionTest extends TestCase
{
    public function test_it_dispatches_status_changed_and_completed_events(): void
    {
        Event::fake();

        $booking = new CleaningBooking([
            'booking_status' => 'pending',
            'company_id' => 15,
        ]);

        $fsm = Mockery::mock(BookingFSMService::class);
        $fsm->shouldReceive('transition')
            ->once()
            ->andReturnUsing(function (CleaningBooking $booking, string $status) {
                $booking->booking_status = $status;
                return $booking;
            });

        $action = new TransitionCleaningBookingAction($fsm);
        $action->execute($booking, 'completed', ['source' => 'test'], 9);

        Event::assertDispatched(BookingStatusChanged::class, function (BookingStatusChanged $event) {
            return $event->fromStatus === 'pending'
                && $event->toStatus === 'completed'
                && $event->companyId === 15
                && $event->actorId === 9;
        });
        Event::assertDispatched(BookingCompleted::class);
        Event::assertNotDispatched(BookingCancelled::class);
    }

    public function test_it_dispatches_cancelled_event_for_cancelled_transition(): void
    {
        Event::fake();

        $booking = new CleaningBooking([
            'booking_status' => 'confirmed',
            'company_id' => 22,
        ]);

        $fsm = Mockery::mock(BookingFSMService::class);
        $fsm->shouldReceive('transition')
            ->once()
            ->andReturnUsing(function (CleaningBooking $booking, string $status) {
                $booking->booking_status = $status;
                return $booking;
            });

        $action = new TransitionCleaningBookingAction($fsm);
        $action->execute($booking, 'cancelled', ['reason' => 'customer'], 11);

        Event::assertDispatched(BookingStatusChanged::class);
        Event::assertDispatched(BookingCancelled::class, function (BookingCancelled $event) {
            return $event->companyId === 22 && $event->actorId === 11 && $event->reason === 'customer';
        });
    }
}
