<?php

namespace Modules\Payroll\Tests\Unit;

use Carbon\CarbonImmutable;
use Modules\Payroll\Services\Domain\CleaningPayrollService;
use Modules\Payroll\Support\DTOs\CleaningPayrollInput;
use PHPUnit\Framework\TestCase;

class CleaningPayrollServiceTest extends TestCase
{
    public function test_it_calculates_cleaner_shift_loadings(): void
    {
        $service = new CleaningPayrollService();
        $result = $service->calculate(new CleaningPayrollInput(1, 10, [[
            'site_code' => 'SHOP-01',
            'started_at' => CarbonImmutable::parse('2026-05-16 09:00:00'),
            'ended_at' => CarbonImmutable::parse('2026-05-16 13:00:00'),
            'hourly_rate' => 30,
        ]]));

        $this->assertSame(120.0, round($result->regularPay, 2));
        $this->assertGreaterThan(0, $result->loadingPay);
        $this->assertSame(4.0, round($result->totalHours, 2));
    }

    public function test_it_warns_for_unapproved_long_shift_without_break(): void
    {
        $service = new CleaningPayrollService();
        $warnings = $service->inspectRosterVariance(new CleaningPayrollInput(1, 10, [[
            'site_code' => 'SITE-1',
            'started_at' => '2026-05-13 08:00:00',
            'ended_at' => '2026-05-13 15:00:00',
            'hourly_rate' => 30,
            'is_approved' => false,
        ]]));

        $this->assertNotEmpty($warnings);
    }
}
