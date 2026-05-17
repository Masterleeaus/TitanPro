<?php

return [
    'assignment_statuses' => ['unassigned', 'scheduled', 'dispatched', 'en_route', 'arrived', 'in_progress', 'paused', 'completed', 'cancelled', 'no_access'],
    'default_status' => 'scheduled',
    'transitions' => [
        'scheduled' => ['dispatched', 'cancelled'],
        'dispatched' => ['en_route', 'cancelled'],
        'en_route' => ['arrived', 'cancelled'],
        'arrived' => ['in_progress', 'no_access'],
        'in_progress' => ['paused', 'completed'],
        'paused' => ['in_progress', 'completed'],
    ],
];
