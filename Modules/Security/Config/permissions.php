<?php

return [
    'module' => 'security',
    'roles' => ['admin', 'security', 'building_manager', 'resident'],
    'permissions' => [
        'security-transfer.view', 'security-transfer.create', 'security-transfer.update', 'security-transfer.delete', 'security-transfer.validate',
        'security-workpermit.view', 'security-workpermit.create', 'security-workpermit.update', 'security-workpermit.delete', 'security-workpermit.validate',
        'trinoutpermit.view', 'trinoutpermit.create', 'trinoutpermit.update', 'trinoutpermit.delete', 'trinoutpermit.approve', 'trinoutpermit.validate',
        'work-permits.view', 'work-permits.create', 'work-permits.update', 'work-permits.delete', 'work-permits.approve', 'work-permits.validate',
        'card-access.view', 'card-access.create', 'card-access.update', 'card-access.delete',
    ],
];
