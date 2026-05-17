<?php

namespace Modules\Biometric\Tests\Unit;

use Modules\Biometric\AI\Tools\DetectAttendanceAnomalyTool;
use PHPUnit\Framework\TestCase;

class DetectAttendanceAnomalyToolTest extends TestCase
{
    public function test_execute_returns_required_shape(): void
    {
        $result = (new DetectAttendanceAnomalyTool())->execute([
            'employee_id' => 99,
            'late_minutes' => 45,
        ]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('anomaly_type', $result);
        $this->assertArrayHasKey('employee_id', $result);
        $this->assertArrayHasKey('confidence', $result);
        $this->assertIsString($result['anomaly_type']);
        $this->assertIsInt($result['employee_id']);
        $this->assertIsFloat($result['confidence']);
    }
}

