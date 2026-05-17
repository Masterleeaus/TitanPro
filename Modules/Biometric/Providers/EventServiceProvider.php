<?php

namespace Modules\Biometric\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Modules\Biometric\Events\AnomalyDetected;
use Modules\Biometric\Events\AttendanceRecorded;
use Modules\Biometric\Events\AttendanceSyncedToHr;
use Modules\Biometric\Events\BiometricClockIn;
use Modules\Biometric\Events\EmployeeDeregisteredFromDevice;
use Modules\Biometric\Events\EmployeeRegisteredOnDevice;
use Modules\Biometric\Events\OvertimeThresholdReached;
use Modules\Biometric\Listeners\AlertOnGeofenceFailure;
use Modules\Biometric\Listeners\DeregisterEmployeeListener;
use Modules\Biometric\Listeners\DetectAnomalyListener;
use Modules\Biometric\Listeners\NotifyClientOnCleanerArrival;
use Modules\Biometric\Listeners\NotifyManagerOnAnomalyListener;
use Modules\Biometric\Listeners\NotifySupervisorOnClockIn;
use Modules\Biometric\Listeners\RecordSignalAuditListener;
use Modules\Biometric\Listeners\RegisterEmployeeOnDeviceListener;
use Modules\Biometric\Listeners\UpdateHrAttendanceListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AttendanceRecorded::class => [
            UpdateHrAttendanceListener::class,
            DetectAnomalyListener::class,
        ],
        AnomalyDetected::class => [
            NotifyManagerOnAnomalyListener::class,
            RecordSignalAuditListener::class,
        ],
        OvertimeThresholdReached::class => [
            NotifyManagerOnAnomalyListener::class,
            RecordSignalAuditListener::class,
        ],
        EmployeeRegisteredOnDevice::class => [
            RecordSignalAuditListener::class,
        ],
        EmployeeDeregisteredFromDevice::class => [
            RecordSignalAuditListener::class,
        ],
        AttendanceSyncedToHr::class => [
            RecordSignalAuditListener::class,
        ],
        BiometricClockIn::class => [
            NotifySupervisorOnClockIn::class,
            NotifyClientOnCleanerArrival::class,
            AlertOnGeofenceFailure::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();

        Event::listen('HRCore.EmployeeOnboarded', RegisterEmployeeOnDeviceListener::class);
        Event::listen('HRCore.EmployeeOffboarded', DeregisterEmployeeListener::class);
    }
}

