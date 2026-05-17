<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Core\PayrollFinalisationGuard;
use PHPUnit\Framework\TestCase;

class PayrollFinalisationGuardTest extends TestCase
{
    public function test_it_blocks_missing_period(): void
    {
        $result = (new PayrollFinalisationGuard())->validate([]);

        $this->assertFalse($result['allowed']);
        $this->assertNotEmpty($result['issues']);
    }
}
