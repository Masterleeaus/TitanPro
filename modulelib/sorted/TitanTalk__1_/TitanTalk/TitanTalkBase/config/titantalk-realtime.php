<?php

declare(strict_types=1);

return [
    'driver' => env('TITANTALK_REALTIME_DRIVER', 'ably'),
    'enabled' => (bool) env('TITANTALK_REALTIME_ENABLED', false),
    'ably_private_key' => env('TITANTALK_ABLY_PRIVATE_KEY', ''),
    'ably_public_key' => env('TITANTALK_ABLY_PUBLIC_KEY', ''),
    'channel_prefix' => env('TITANTALK_REALTIME_PREFIX', 'titantalk-'),
];
