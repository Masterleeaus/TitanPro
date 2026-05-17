<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Domain\EmployeeSelfServicePayrollService;
use PHPUnit\Framework\TestCase;

class EmployeeSelfServicePayrollServiceTest extends TestCase
{
    public function test_it_masks_bank_account_updates(): void
    {
        $service = new EmployeeSelfServicePayrollService();

        $result = $service->updateBankDetails(5, [
            'account_name' => 'Cleaner One',
            'account_number' => '123456789',
        ]);

        $this->assertTrue($result['accepted']);
        $this->assertSame('*****6789', $result['masked_account']);
    }
}
