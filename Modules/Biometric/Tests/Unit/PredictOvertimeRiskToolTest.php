<?php

namespace Modules\Biometric\Tests\Unit;

use Modules\Biometric\AI\Tools\PredictOvertimeRiskTool;
use PHPUnit\Framework\TestCase;

class PredictOvertimeRiskToolTest extends TestCase
{
    public function test_execute_returns_required_shape(): void
    {
        $result = (new PredictOvertimeRiskTool())->execute([
            'worked_hours' => 11.5,
            'scheduled_hours' => 8,
        ]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('risk_level', $result);
        $this->assertArrayHasKey('projected_hours', $result);
        $this->assertIsString($result['risk_level']);
        $this->assertIsFloat($result['projected_hours']);
    }
}

