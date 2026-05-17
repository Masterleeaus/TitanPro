<?php

return [
    'callback_due_minutes' => (int) env('TITANNEXUS_CALLBACK_DUE_MINUTES', 30),
    'recording_retention_days' => (int) env('TITANNEXUS_RECORDING_RETENTION_DAYS', 90),
    'lead_extraction' => [
        'minimum_phone_digits' => 8,
        'default_status' => 'new',
    ],
];
