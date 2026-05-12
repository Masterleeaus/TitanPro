<?php

namespace Modules\Biometric\Tests\Feature;

use Modules\Biometric\Providers\EventServiceProvider;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

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

        foreach ($listen as $eventClass => $listeners) {
            $this->assertIsString($eventClass);
            $this->assertNotEmpty($listeners, "Event {$eventClass} must have at least one listener.");
            $this->assertIsArray($listeners);
        }
    }
}

