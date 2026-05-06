<?php

namespace Modules\BookingModule\Providers;

use App\Events\NewCompanyCreatedEvent;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\BookingModule\Events\AppointmentStatus;
use Modules\BookingModule\Events\BookingCancelled;
use Modules\BookingModule\Events\BookingCompleted;
use Modules\BookingModule\Events\BookingRequested;
use Modules\BookingModule\Events\BookingStatusChanged;
use Modules\BookingModule\Events\ScheduleAssigned;
use Modules\BookingModule\Events\ScheduleRescheduled;
use Modules\BookingModule\Listeners\AppointmentStatusListener;
use Modules\BookingModule\Listeners\BookingCompletedListener;
use Modules\BookingModule\Listeners\CompanyCreatedListener;
use Modules\BookingModule\Listeners\EmitBookingRequestedSignalToTitanZero;
use Modules\BookingModule\Listeners\RecordBookingLifecycleLog;
use Modules\BookingModule\Listeners\SendBookingLifecycleMail;
use Modules\BookingModule\Listeners\SendBookingRequestEmail;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewCompanyCreatedEvent::class => [
            CompanyCreatedListener::class,
        ],
        BookingRequested::class => [
            SendBookingRequestEmail::class,
            EmitBookingRequestedSignalToTitanZero::class,
        ],
        BookingCompleted::class => [
            BookingCompletedListener::class,
            RecordBookingLifecycleLog::class,
            SendBookingLifecycleMail::class,
        ],
        AppointmentStatus::class => [
            AppointmentStatusListener::class,
        ],
        BookingStatusChanged::class => [
            RecordBookingLifecycleLog::class,
        ],
        BookingCancelled::class => [
            RecordBookingLifecycleLog::class,
            SendBookingLifecycleMail::class,
        ],
        ScheduleAssigned::class => [
            RecordBookingLifecycleLog::class,
            SendBookingLifecycleMail::class,
        ],
        ScheduleRescheduled::class => [
            RecordBookingLifecycleLog::class,
            SendBookingLifecycleMail::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
