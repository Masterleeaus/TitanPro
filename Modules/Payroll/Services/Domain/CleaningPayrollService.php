<?php

namespace Modules\Payroll\Services\Domain;

use Modules\Payroll\Contracts\Services\CleaningPayrollServiceContract;
use Modules\Payroll\Support\DTOs\CleaningPayrollInput;
use Modules\Payroll\Support\DTOs\CleaningPayrollResult;
use Modules\Payroll\Support\DTOs\CleaningShift;

class CleaningPayrollService implements CleaningPayrollServiceContract
{
    public function calculate(CleaningPayrollInput $input): CleaningPayrollResult
    {
        $regularPay = 0.0;
        $loadingPay = 0.0;
        $allowances = 0.0;
        $hours = 0.0;
        $lines = [];

        foreach ($input->normalizedShifts() as $shift) {
            $shiftHours = $shift->paidHours();
            $base = round($shiftHours * $shift->hourlyRate, 2);
            $loadingRate = $this->loadingRateFor($shift);
            $loading = round($base * $loadingRate, 2);
            $shiftAllowances = $this->allowanceFor($shift);

            $hours += $shiftHours;
            $regularPay += $base;
            $loadingPay += $loading;
            $allowances += $shiftAllowances;

            $lines[] = [
                'code' => 'CLEAN_SHIFT',
                'label' => 'Cleaning shift '.$shift->siteCode,
                'site_code' => $shift->siteCode,
                'hours' => round($shiftHours, 4),
                'base_amount' => $base,
                'loading_rate' => $loadingRate,
                'loading_amount' => $loading,
                'allowance_amount' => round($shiftAllowances, 2),
            ];
        }

        foreach ($input->extraEarnings as $earning) {
            $amount = (float) (is_array($earning) ? ($earning['amount'] ?? 0) : $earning);
            $regularPay += $amount;
            $lines[] = ['code' => 'EXTRA', 'label' => is_array($earning) ? ($earning['label'] ?? 'Extra earning') : 'Extra earning', 'amount' => round($amount, 2)];
        }

        $deductions = $this->sum($input->deductions);
        $withholding = $input->isContractor ? round(($regularPay + $loadingPay + $allowances) * (float) config('payroll.cleaning.contractors.default_withholding_rate', 0), 2) : 0.0;
        $gross = round($regularPay + $loadingPay + $allowances, 2);
        $net = max(0, round($gross - $deductions - $withholding, 2));
        $warnings = $this->inspectRosterVariance($input);

        return new CleaningPayrollResult(
            companyId: $input->companyId,
            userId: $input->userId,
            regularPay: $regularPay,
            loadingPay: $loadingPay,
            allowances: $allowances,
            deductions: $deductions + $withholding,
            grossPay: $gross,
            netPay: $net,
            totalHours: $hours,
            lines: $lines,
            warnings: $warnings,
            metadata: array_merge($input->metadata, ['contractor' => $input->isContractor, 'withholding' => $withholding]),
        );
    }

    public function inspectRosterVariance(CleaningPayrollInput $input): array
    {
        $warnings = [];
        $maxWithoutBreak = (float) config('payroll.cleaning.variance.max_hours_without_break', 5.0);
        foreach ($input->normalizedShifts() as $index => $shift) {
            if ($shift->siteCode === 'unknown' && config('payroll.cleaning.variance.missing_site_is_warning', true)) {
                $warnings[] = "Shift {$index} is missing a site code.";
            }
            if (! $shift->isApproved && config('payroll.cleaning.variance.unapproved_shift_is_warning', true)) {
                $warnings[] = "Shift {$index} has not been approved.";
            }
            if ($shift->paidHours() > $maxWithoutBreak && empty($shift->breakMinutes)) {
                $warnings[] = "Shift {$index} exceeds {$maxWithoutBreak} hours without a recorded break.";
            }
        }
        return $warnings;
    }

    private function loadingRateFor(CleaningShift $shift): float
    {
        if ($shift->isPublicHoliday) {
            return (float) config('payroll.cleaning.loadings.public_holiday', 1.5);
        }
        if ($shift->startedAt->isSunday()) {
            return (float) config('payroll.cleaning.loadings.sunday', 0.5);
        }
        if ($shift->startedAt->isSaturday()) {
            return (float) config('payroll.cleaning.loadings.saturday', 0.25);
        }
        if ((int) $shift->startedAt->format('H') < 6 || (int) $shift->startedAt->format('H') >= 20) {
            return (float) config('payroll.cleaning.loadings.night', 0.15);
        }
        return (float) config('payroll.cleaning.loadings.weekday', 0.0);
    }

    private function allowanceFor(CleaningShift $shift): float
    {
        return ($shift->requiresTravelAllowance ? (float) config('payroll.cleaning.allowances.travel_per_shift', 0) : 0)
            + ($shift->requiresSiteAllowance ? (float) config('payroll.cleaning.allowances.site_per_shift', 0) : 0)
            + ($shift->requiresEquipmentAllowance ? (float) config('payroll.cleaning.allowances.equipment_per_shift', 0) : 0);
    }

    private function sum(array $items): float
    {
        return array_reduce($items, fn ($carry, $item) => $carry + (float) (is_array($item) ? ($item['amount'] ?? 0) : $item), 0.0);
    }
}
