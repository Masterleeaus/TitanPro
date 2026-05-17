<?php

return [
    'enabled' => env('PAYROLL_CLEANING_ENABLED', true),
    'award_profile' => env('PAYROLL_CLEANING_AWARD_PROFILE', 'cleaning_mvp'),
    'default_currency' => env('PAYROLL_CLEANING_CURRENCY', 'AUD'),

    'loadings' => [
        'weekday' => (float) env('PAYROLL_CLEANING_WEEKDAY_LOADING', 0.00),
        'night' => (float) env('PAYROLL_CLEANING_NIGHT_LOADING', 0.15),
        'saturday' => (float) env('PAYROLL_CLEANING_SATURDAY_LOADING', 0.25),
        'sunday' => (float) env('PAYROLL_CLEANING_SUNDAY_LOADING', 0.50),
        'public_holiday' => (float) env('PAYROLL_CLEANING_PUBLIC_HOLIDAY_LOADING', 1.50),
    ],

    'allowances' => [
        'travel_per_shift' => (float) env('PAYROLL_CLEANING_TRAVEL_ALLOWANCE', 0.00),
        'site_per_shift' => (float) env('PAYROLL_CLEANING_SITE_ALLOWANCE', 0.00),
        'equipment_per_shift' => (float) env('PAYROLL_CLEANING_EQUIPMENT_ALLOWANCE', 0.00),
    ],

    'variance' => [
        'missing_site_is_warning' => true,
        'unapproved_shift_is_warning' => true,
        'max_hours_without_break' => (float) env('PAYROLL_CLEANING_MAX_HOURS_WITHOUT_BREAK', 5.0),
    ],

    'contractors' => [
        'enabled' => env('PAYROLL_CLEANING_CONTRACTORS_ENABLED', true),
        'default_withholding_rate' => (float) env('PAYROLL_CLEANING_CONTRACTOR_WITHHOLDING_RATE', 0.00),
        'emit_invoice_style_payout' => true,
    ],

    'self_service' => [
        'allow_payslip_download' => env('PAYROLL_ESS_PAYSLIP_DOWNLOAD', true),
        'allow_bank_detail_update' => env('PAYROLL_ESS_BANK_UPDATE', true),
        'require_bank_change_approval' => env('PAYROLL_ESS_BANK_APPROVAL', true),
        'show_tax_declaration_status' => true,
    ],
    'reconciliation' => [
        'hour_tolerance' => env('PAYROLL_CLEANING_RECONCILIATION_HOUR_TOLERANCE', 0.25),
    ],
];
