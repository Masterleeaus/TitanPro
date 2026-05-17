<?php

namespace Modules\Payroll\Services\Domain;

use InvalidArgumentException;
use Modules\Payroll\Contracts\Services\CleaningPayrollSettingsServiceContract;

class CleaningPayrollSettingsService implements CleaningPayrollSettingsServiceContract
{
    public function defaults(?int $companyId = null): array
    {
        return [
            'company_id' => $companyId,
            'loadings' => config('payroll.cleaning.loadings', []),
            'allowances' => config('payroll.cleaning.allowances', []),
            'variance' => config('payroll.cleaning.variance', []),
            'contractors' => config('payroll.cleaning.contractors', []),
            'self_service' => config('payroll.cleaning.self_service', [
                'allow_payslip_download' => true,
                'allow_bank_detail_update' => true,
                'require_bank_change_approval' => true,
            ]),
        ];
    }

    public function normalize(array $settings, ?int $companyId = null): array
    {
        $merged = array_replace_recursive($this->defaults($companyId), $settings);
        $this->validate($merged);

        foreach (['weekday', 'night', 'saturday', 'sunday', 'public_holiday'] as $key) {
            $merged['loadings'][$key] = round((float) ($merged['loadings'][$key] ?? 0), 4);
        }

        foreach (['travel_per_shift', 'site_per_shift', 'equipment_per_shift'] as $key) {
            $merged['allowances'][$key] = round((float) ($merged['allowances'][$key] ?? 0), 2);
        }

        $merged['variance']['max_hours_without_break'] = round((float) ($merged['variance']['max_hours_without_break'] ?? 5), 2);
        $merged['contractors']['default_withholding_rate'] = round((float) ($merged['contractors']['default_withholding_rate'] ?? 0), 4);

        return $merged;
    }

    public function validate(array $settings): array
    {
        foreach (($settings['loadings'] ?? []) as $name => $rate) {
            if ((float) $rate < 0 || (float) $rate > 3) {
                throw new InvalidArgumentException("Cleaning loading {$name} must be between 0 and 3.");
            }
        }

        foreach (($settings['allowances'] ?? []) as $name => $amount) {
            if ((float) $amount < 0 || (float) $amount > 500) {
                throw new InvalidArgumentException("Cleaning allowance {$name} must be between 0 and 500.");
            }
        }

        return ['valid' => true];
    }
}
