<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Exporters\PayrollJournalExportService;
use PHPUnit\Framework\TestCase;

class PayrollJournalExportServiceTest extends TestCase
{
    public function test_it_builds_balanced_journal_lines(): void
    {
        $journal = (new PayrollJournalExportService())->build([
            'period_from' => '2026-05-01',
            'period_to' => '2026-05-31',
            'gross_total' => 1000,
            'net_total' => 750,
            'tax_total' => 250,
        ]);

        $debits = array_sum(array_column($journal['lines'], 'debit'));
        $credits = array_sum(array_column($journal['lines'], 'credit'));
        $this->assertSame($debits, $credits);
    }
}
