<?php

return [
    'features' => [
        'security_transfer' => true,
        'goods_in_out_permit' => true,
        'work_permits' => true,
        'work_permit_files' => true,
        'access_cards' => true,
        'pdf_exports' => true,
        'approval_workflows' => true,
        'security_validation' => true,
        'legacy_route_aliases' => true,
        'api_dashboard' => true,
        'health_checks' => true,
    ],
    'modules' => [
        'security_transfer' => ['route' => 'security-transfer.index', 'permission' => 'view_security'],
        'goods_in_out_permit' => ['route' => 'trinoutpermit.index', 'permission' => 'view_trinoutpermit'],
        'work_permits' => ['route' => 'work-permits.index', 'permission' => 'view_work_permits'],
        'access_cards' => ['route' => 'card-access.index', 'permission' => 'view_access_card'],
    ],
];
