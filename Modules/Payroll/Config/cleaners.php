<?php

return [
    'default_pay_frequency' => env('PAYROLL_CLEANERS_PAY_FREQUENCY', 'weekly'),
    'require_site_reconciliation_before_finalise' => env('PAYROLL_REQUIRE_SITE_RECONCILIATION', true),
    'require_payslip_delivery_on_finalise' => env('PAYROLL_REQUIRE_PAYSLIP_DELIVERY', true),
    'allow_contractor_payouts' => env('PAYROLL_ALLOW_CONTRACTOR_PAYOUTS', true),
    'loadings' => [
        'night' => (float) env('PAYROLL_NIGHT_LOADING', 0.15),
        'saturday' => (float) env('PAYROLL_SATURDAY_LOADING', 0.25),
        'sunday' => (float) env('PAYROLL_SUNDAY_LOADING', 0.50),
        'public_holiday' => (float) env('PAYROLL_PUBLIC_HOLIDAY_LOADING', 1.00),
    ],
    'allowances' => [
        'travel_per_shift' => (float) env('PAYROLL_TRAVEL_ALLOWANCE_PER_SHIFT', 0.00),
        'site_per_shift' => (float) env('PAYROLL_SITE_ALLOWANCE_PER_SHIFT', 0.00),
        'equipment_per_shift' => (float) env('PAYROLL_EQUIPMENT_ALLOWANCE_PER_SHIFT', 0.00),
    ],
];
