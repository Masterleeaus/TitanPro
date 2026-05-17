<?php

namespace Modules\Payroll\Filament\Resources;

class CleanerPayrollRunResource
{
    public static string $model = 'Modules\\Payroll\\Entities\\PayrollRun';
    public static string $navigationGroup = 'Payroll';
    public static string $navigationLabel = 'Cleaner Payroll Runs';

    public static function tableColumns(): array
    {
        return ['id', 'company_id', 'period_start', 'period_end', 'status', 'gross_total', 'net_total', 'created_at'];
    }

    public static function filters(): array
    {
        return ['status', 'period_start', 'period_end', 'company_id'];
    }

    public static function actions(): array
    {
        return ['preview', 'submit', 'approve', 'reject', 'lock_period', 'export_bank_file', 'send_payslips'];
    }
}
