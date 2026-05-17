<?php

namespace Modules\Payroll\Tests\Unit;

use Carbon\Carbon;
use Modules\Payroll\Services\Core\PayrollCalculationService;
use Modules\Payroll\Support\DTOs\PayrollCalculationInput;
use PHPUnit\Framework\TestCase;

class PayrollCalculationServiceTest extends TestCase
{
    public function test_it_calculates_gross_and_net_pay(): void
    {
        $service = new PayrollCalculationService();
        $result = $service->calculate(new PayrollCalculationInput(companyId: 1, userId: 10, from: Carbon::parse('2026-05-01'), to: Carbon::parse('2026-05-31'), baseSalary: 5000, earnings: [['amount' => 250]], deductions: [['amount' => 100]], taxes: [['amount' => 900]], reimbursements: [['amount' => 50]]));
        $this->assertSame(5250.0, $result->grossPay);
        $this->assertSame(4300.0, $result->netPay);
    }
}
