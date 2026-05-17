<?php

namespace Modules\Payroll\Tests\Feature;

use Modules\Payroll\Services\Domain\PayrollRunGuardService;
use PHPUnit\Framework\TestCase;

class PayrollRunGuardTest extends TestCase
{
    public function test_it_rejects_missing_period(): void
    {
        $result = (new PayrollRunGuardService())->inspect(['company_id' => 1]);
        $this->assertFalse($result->allowed);
        $this->assertNotEmpty($result->errors);
    }
}
