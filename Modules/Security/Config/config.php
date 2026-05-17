<?php

return [
    'name' => 'Security',

    'uploads' => [
        'max_kb' => env('SECURITY_UPLOAD_MAX_KB', 5120),
        'allowed_mimes' => [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'image/webp',
        ],
        'blocked_extensions' => [
            'php', 'phtml', 'phar', 'js', 'html', 'htm', 'svg', 'exe', 'sh', 'bat', 'cmd', 'com', 'scr',
        ],
    ],

    'rate_limits' => [
        'api_per_minute' => env('SECURITY_API_RATE_LIMIT_PER_MINUTE', 60),
        'uploads_per_minute' => env('SECURITY_UPLOAD_RATE_LIMIT_PER_MINUTE', 10),
    ],

    'audit' => [
        'redact_sensitive_values' => true,
    ],
];
