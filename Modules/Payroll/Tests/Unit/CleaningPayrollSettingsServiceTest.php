<?php

namespace Modules\Payroll\Tests\Unit;

use Modules\Payroll\Services\Domain\CleaningPayrollSettingsService;
use PHPUnit\Framework\TestCase;

class CleaningPayrollSettingsServiceTest extends TestCase
{
    public function test_it_normalizes_cleaner_payroll_settings(): void
    {
        $service = new CleaningPayrollSettingsService();

        $settings = $service->normalize([
            'loadings' => ['saturday' => '0.275'],
            'allowances' => ['travel_per_shift' => '12.50'],
            'variance' => ['max_hours_without_break' => '4.5'],
        ], 10);

        $this->assertSame(10, $settings['company_id']);
        $this->assertSame(0.275, $settings['loadings']['saturday']);
        $this->assertSame(12.50, $settings['allowances']['travel_per_shift']);
        $this->assertSame(4.5, $settings['variance']['max_hours_without_break']);
    }
}
