<?php

return [
    'filament_panel' => 'titanpro',
    'guards' => [
        'super_admin' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],
    'routes' => [
        'web_prefix' => 'admin/titanpro-admin',
        'api_prefix' => 'titanpro-admin',
    ],
];
