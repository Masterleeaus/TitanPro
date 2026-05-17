<?php

namespace Modules\Biometric\Tests\Feature;

use Modules\Biometric\Providers\EventServiceProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Modules\Biometric\Events\AnomalyDetected;
use Modules\Biometric\Events\AttendanceRecorded;
use Modules\Biometric\Events\AttendanceSyncedToHr;
use Modules\Biometric\Events\BiometricClockIn;
use Modules\Biometric\Events\EmployeeDeregisteredFromDevice;
use Modules\Biometric\Events\EmployeeRegisteredOnDevice;
use Modules\Biometric\Events\OvertimeThresholdReached;

class BiometricFilamentAndEventsTest extends TestCase
{
    public function test_filament_artifacts_exist(): void
    {
        $base = dirname(__DIR__, 2);

        $this->assertFileExists($base . '/Filament/Resources/AttendanceResource.php');
        $this->assertFileExists($base . '/Filament/Resources/BiometricDeviceResource.php');
        $this->assertFileExists($base . '/Filament/Pages/AttendanceDashboardPage.php');
        $this->assertFileExists($base . '/Filament/Widgets/ShiftCoverageWidget.php');
        $this->assertFileExists($base . '/Filament/Widgets/OvertimeAlertWidget.php');
    }

    public function test_all_seven_events_are_wired_to_listeners(): void
    {
        $listen = (new ReflectionClass(EventServiceProvider::class))->getDefaultProperties()['listen'] ?? [];

        $this->assertCount(7, $listen);
        $this->assertArrayHasKey(AttendanceRecorded::class, $listen);
        $this->assertArrayHasKey(AnomalyDetected::class, $listen);
        $this->assertArrayHasKey(OvertimeThresholdReached::class, $listen);
        $this->assertArrayHasKey(EmployeeRegisteredOnDevice::class, $listen);
        $this->assertArrayHasKey(EmployeeDeregisteredFromDevice::class, $listen);
        $this->assertArrayHasKey(AttendanceSyncedToHr::class, $listen);
        $this->assertArrayHasKey(BiometricClockIn::class, $listen);

        foreach ($listen as $eventClass => $listeners) {
            $this->assertIsString($eventClass);
            $this->assertNotEmpty($listeners, "Event {$eventClass} must have at least one listener.");
            $this->assertIsArray($listeners);
        }
    }
}
