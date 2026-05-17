<?php

return [
    'recurrence'    => env('TITANGO_FEATURE_RECURRENCE', true),
    'client_portal' => env('TITANGO_FEATURE_CLIENT_PORTAL', true),
    'auto_invoice'  => env('TITANGO_FEATURE_AUTO_INVOICE', false),
    'webhooks'      => env('TITANGO_FEATURE_WEBHOOKS', false),
    'voice'         => env('TITANGO_FEATURE_VOICE', false),
    'ai_assistant'  => env('TITANGO_FEATURE_AI_ASSISTANT', true),
    'sla_tracking'  => env('TITANGO_FEATURE_SLA', true),
    'catalog'       => env('TITANGO_FEATURE_CATALOG', true),
];
