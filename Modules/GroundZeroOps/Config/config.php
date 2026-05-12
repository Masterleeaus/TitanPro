<?php

return [
    'name' => 'GroundZeroOps',
    'filament_panel' => 'groundzero',
    'routes' => [
        'api_middleware' => ['api', 'auth:sanctum'],
        'web_middleware' => ['web', 'auth'],
    ],
];
