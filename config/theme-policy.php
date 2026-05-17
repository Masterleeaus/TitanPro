<?php

return [
    'enabled' => env('THEME_POLICY_ENABLED', true),
    'priority' => ['tenant', 'user', 'role', 'fallback'],
];
