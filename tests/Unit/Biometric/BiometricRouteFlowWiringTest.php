<?php

namespace Tests\Unit\Biometric;

use PHPUnit\Framework\TestCase;

class BiometricRouteFlowWiringTest extends TestCase
{
    public function test_biometric_web_flow_controller_actions_referenced_by_routes_exist(): void
    {
        $deviceController = file_get_contents(dirname(__DIR__, 3).'/Modules/Biometric/Http/Controllers/BiometricDeviceController.php');
        $employeeController = file_get_contents(dirname(__DIR__, 3).'/Modules/Biometric/Http/Controllers/BiometricEmployeeController.php');

        $this->assertStringContainsString('function changeStatus(', (string) $deviceController);
        $this->assertStringContainsString('function syncEmployees(', (string) $deviceController);
        $this->assertStringContainsString('function fetchAll(', (string) $employeeController);
        $this->assertStringContainsString('function getEmployeeInfo(', (string) $employeeController);
    }

    public function test_biometric_device_api_flow_actions_referenced_by_routes_exist(): void
    {
        $apiController = file_get_contents(dirname(__DIR__, 3).'/Modules/Biometric/Http/Controllers/ZKTecoController.php');

        $this->assertStringContainsString('function handshake(', (string) $apiController);
        $this->assertStringContainsString('function handleAttendanceData(', (string) $apiController);
        $this->assertStringContainsString('function handleGetRequest(', (string) $apiController);
        $this->assertStringContainsString('function handleDeviceCommand(', (string) $apiController);
        $this->assertStringContainsString('function handlePing(', (string) $apiController);
        $this->assertStringContainsString('function test(', (string) $apiController);
    }
}
