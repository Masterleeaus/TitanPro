<?php

return [
    'enabled' => env('SECURITY_CLEANERS_ENABLED', true),
    'default_status' => 'pending',
    'check_in_grace_minutes' => (int) env('SECURITY_CLEANER_CHECKIN_GRACE', 15),
    'id_prefix' => env('SECURITY_CLEANER_ID_PREFIX', 'CLN'),
    'features' => [
        'cleaner_registration' => true,
        'access_cards' => true,
        'site_checkpoints' => true,
        'work_permits' => true,
        'goods_in_out' => true,
        'supervisor_approval' => true,
        'basic_reports' => true,
    ],
    'statuses' => ['pending', 'active', 'rejected', 'suspended', 'archived'],
    'approval_statuses' => ['draft', 'submitted', 'approved', 'rejected', 'cancelled'],
    'max_open_session_hours' => env('SECURITY_CLEANER_MAX_OPEN_SESSION_HOURS', 16),
    'allow_force_checkout' => env('SECURITY_CLEANER_ALLOW_FORCE_CHECKOUT', true),
];
