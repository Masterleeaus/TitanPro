<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Analytics\PayrollVarianceService;
use PHPUnit\Framework\TestCase;

class PayrollVarianceServiceTest extends TestCase
{
    public function test_it_flags_large_variance(): void
    {
        $result = (new PayrollVarianceService())->compare(
            ['gross_total' => 1200, 'net_total' => 900, 'processed_count' => 10],
            ['gross_total' => 1000, 'net_total' => 850, 'processed_count' => 10],
            ['threshold_percent' => 10]
        );

        $this->assertFalse($result['passed']);
        $this->assertTrue($result['variances']['gross_total']['flagged']);
    }
}
