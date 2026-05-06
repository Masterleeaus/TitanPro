<?php

namespace Modules\BookingModule\Enums;

enum BookingLifecycleEventType: string
{
    case Requested = 'booking_requested';
    case StatusChanged = 'booking_status_changed';
    case Completed = 'booking_completed';
    case Cancelled = 'booking_cancelled';
    case ScheduleAssigned = 'schedule_assigned';
    case ScheduleRescheduled = 'schedule_rescheduled';
}
