<?php

return [
    'readiness' => [
        'required_config' => [
            'app.key',
            'database.default',
            'queue.default',
            'filesystems.default',
        ],
        'required_directories' => [
            'Storage/Documents',
            'Storage/Temp',
            'Storage/Exports',
            'Temp/Runtime',
            'Temp/Queue',
        ],
        'recommended_cache_keys' => [
            'security:features',
            'security:permissions',
            'security:health',
        ],
    ],

    'performance' => [
        'cache_ttl_seconds' => env('SECURITY_CACHE_TTL', 300),
        'dashboard_limit' => env('SECURITY_DASHBOARD_LIMIT', 25),
        'bulk_chunk_size' => env('SECURITY_BULK_CHUNK_SIZE', 100),
    ],

    'deployment' => [
        'fail_on_degraded_health' => env('SECURITY_FAIL_ON_DEGRADED_HEALTH', true),
        'verify_before_migrate' => env('SECURITY_VERIFY_BEFORE_MIGRATE', true),
    ],
];
