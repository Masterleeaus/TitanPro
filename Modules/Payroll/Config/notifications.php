<?php

return [
    'approval_requested' => ['mail', 'database'],
    'run_approved' => ['mail', 'database'],
    'run_rejected' => ['mail', 'database'],
    'variance_detected' => ['database'],
    'payslip_created' => ['mail', 'database'],
    'payslip_link_expiry_minutes' => env('PAYROLL_PAYSLIP_LINK_EXPIRY_MINUTES', 10080),
    'employee_payslip_preferences' => [
        'enabled' => true,
        'channels' => ['mail', 'database'],
        'require_acknowledgement' => true,
    ],
];
