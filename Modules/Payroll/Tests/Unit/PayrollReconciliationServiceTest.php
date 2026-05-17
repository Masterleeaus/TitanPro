<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Integrations\PayrollReconciliationService;
use PHPUnit\Framework\TestCase;

class PayrollReconciliationServiceTest extends TestCase
{
    public function test_it_reports_variances(): void
    {
        $result = (new PayrollReconciliationService())->reconcile([['user_id' => 1, 'amount' => 100]], [['user_id' => 1, 'amount' => 110]]);
        $this->assertCount(1, $result['variance']);
    }
}
