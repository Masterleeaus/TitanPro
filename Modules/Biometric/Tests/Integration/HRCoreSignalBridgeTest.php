<?php

namespace Modules\Biometric\Tests\Integration;

use Modules\Biometric\Listeners\DeregisterEmployeeListener;
use Modules\Biometric\Listeners\RegisterEmployeeOnDeviceListener;
use PHPUnit\Framework\TestCase;

class HRCoreSignalBridgeTest extends TestCase
{
    public function test_hrcore_signal_listeners_exist(): void
    {
        $this->assertTrue(class_exists(RegisterEmployeeOnDeviceListener::class));
        $this->assertTrue(class_exists(DeregisterEmployeeListener::class));
    }

    public function test_event_provider_boot_registers_hrcore_signals(): void
    {
        $providerPath = dirname(__DIR__, 2) . '/Providers/EventServiceProvider.php';
        $providerCode = (string) file_get_contents($providerPath);

        $this->assertStringContainsString('HRCore.EmployeeOnboarded', $providerCode);
        $this->assertStringContainsString('HRCore.EmployeeOffboarded', $providerCode);
    }
}

