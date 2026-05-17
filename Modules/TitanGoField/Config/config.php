<?php

return [
    'name' => 'TitanGoField',

    'default_vertical' => env('FSM_DEFAULT_VERTICAL', 'general_trades'),

    'features' => [
        'recurrence'   => true,
        'quotes'       => true,
        'auto_invoice' => false,
        'webhooks'     => false,
        'assets'       => true,
        'permits'      => true,
        'checklists'   => true,
        'inspections'  => true,
        'client_portal' => true,
        'voice'        => env('TITANGO_VOICE_ENABLED', false),
    ],

    'branding' => [
        'pdf_header'  => null,
        'pdf_footer'  => null,
        'logo_path'   => null,
    ],

    'webhook_url' => env('FSM_WEBHOOK_URL'),

    'models' => [
        'client'  => \App\Models\User::class,
        'user'    => \App\Models\User::class,
        'project' => null,
        'task'    => null,
    ],
];
