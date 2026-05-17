<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Domain\CleanerShiftReconciliationService;
use PHPUnit\Framework\TestCase;

class CleanerShiftReconciliationServiceTest extends TestCase
{
    public function test_it_detects_missing_time_entries(): void
    {
        $result = (new CleanerShiftReconciliationService())->compare([
            ['id' => 10, 'hours' => 4],
        ], []);

        $this->assertFalse($result['passed']);
        $this->assertSame('missing_time_entry', $result['issues'][0]['type']);
    }
}
