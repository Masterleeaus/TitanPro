<?php

namespace Modules\Payroll\Filament\Pages;

class CleaningPayrollSettingsPage
{
    public static string $title = 'Cleaning Payroll Settings';
    public static string $navigationGroup = 'Payroll';
    public static string $navigationIcon = 'heroicon-o-adjustments-horizontal';

    public function schema(): array
    {
        return [
            'loadings' => ['weekday', 'night', 'saturday', 'sunday', 'public_holiday'],
            'allowances' => ['travel_per_shift', 'site_per_shift', 'equipment_per_shift'],
            'variance' => ['max_hours_without_break', 'missing_site_is_warning', 'unapproved_shift_is_warning'],
            'self_service' => ['allow_payslip_download', 'allow_bank_detail_update', 'require_bank_change_approval'],
        ];
    }
}
