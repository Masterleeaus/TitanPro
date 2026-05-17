<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Domain\PayrollTaxService;
use PHPUnit\Framework\TestCase;

class PayrollTaxServiceTest extends TestCase
{
    public function test_it_estimates_tax_with_configured_band(): void
    {
        if (! function_exists('config')) {
            $this->markTestSkipped('Laravel config helper is required.');
        }
        config(['payroll.tax.bands.AU' => [['from' => 0, 'to' => null, 'rate' => 0.1, 'base' => 0, 'label' => 'flat']]]);
        $result = (new PayrollTaxService())->estimate(1000, 'AU');
        $this->assertSame(100.0, $result['tax']);
    }
}
