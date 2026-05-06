<?php

return [
    // Route prefixes updated for TitanWork. Web routes will be served under /titanwork
    'prefix' => 'titanwork',
    'api_prefix' => 'api/titanwork',
    'middleware' => ['web', 'auth'],
    'api_middleware' => ['api', 'auth:sanctum'],
];
