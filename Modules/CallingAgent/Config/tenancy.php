<?php

return [
    'enabled' => env('CALLING_AGENT_TENANCY_ENABLED', true),
    'tenant_column' => 'tenant_id',
    'resolver' => 'phone_number_then_channel',
];
