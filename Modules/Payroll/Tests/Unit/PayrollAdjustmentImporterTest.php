<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Importers\PayrollAdjustmentImporter;
use Tests\TestCase;

class PayrollAdjustmentImporterTest extends TestCase
{
    public function test_it_parses_adjustment_csv(): void
    {
        $csv = "employee_id,code,amount,description\n10,BONUS,125.50,Quarterly bonus";
        $rows = (new PayrollAdjustmentImporter())->parseCsv($csv);

        $this->assertSame(10, $rows[0]['employee_id']);
        $this->assertSame('BONUS', $rows[0]['code']);
        $this->assertSame(125.50, $rows[0]['amount']);
    }
}
