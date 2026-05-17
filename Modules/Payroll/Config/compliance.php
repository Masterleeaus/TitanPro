<?php

return [
    'minimum_net_pay' => env('PAYROLL_MINIMUM_NET_PAY', 0),
    'maximum_net_to_gross_ratio' => env('PAYROLL_MAX_NET_GROSS_RATIO', 1.25),
    'require_employee_identifier' => true,
    'warn_overtime_hours_above' => 20,
    'countries' => ['AU', 'NZ', 'US', 'GB'],
];
