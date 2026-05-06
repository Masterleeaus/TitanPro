<?php

return [
    'endpoint' => env('ZEROPAY_ENDPOINT'),
    'token' => env('ZEROPAY_TOKEN'),
    'integration_mode' => 'external_handoff_only',
    'auto_send_invoices' => true,
    'late_followup_days' => [3, 7, 14, 21, 30],
];
