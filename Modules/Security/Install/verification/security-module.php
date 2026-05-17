<?php

return [
    'module' => 'Security',
    'commands' => [
        'php artisan module:migrate Security',
        'php artisan route:clear',
        'php artisan config:clear',
        'php artisan security:health',
    ],
    'required_tables' => [
        'tr_in_out_permit',
        'tr_workpermits',
        'tr_workpermit_files',
        'tr_access_card',
        'tr_access_card_items',
    ],
    'required_routes' => [
        'api.security.dashboard',
        'api.security.health',
        'api.security.status',
        'api.security.diagnostics',
    ],
];
