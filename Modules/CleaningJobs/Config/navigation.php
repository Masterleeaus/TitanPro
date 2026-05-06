<?php

return [
    'items' => [[
        'label' => 'Cleaning Jobs',
        'route' => 'cleaningjobs.index',
        'permission' => 'cleaningjobs.view'
    ], [
        'label' => 'Requests',
        'route' => 'cleaningjobs.requests.index',
        'permission' => 'cleaningjobs.view'
    ], [
        'label' => 'Service Catalog',
        'route' => 'cleaningjobs.service-parts.index',
        'permission' => 'cleaningjobs.view'
    ]]
];
