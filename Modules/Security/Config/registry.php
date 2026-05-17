<?php

return [
    'name' => 'Security',
    'alias' => 'security',
    'category' => 'operations',
    'health_checks' => ['database_tables', 'routes', 'permissions', 'features'],
    'dependencies' => ['Units', 'Company', 'User'],
    'legacy_aliases' => ['trinoutpermit', 'trworkpermits', 'traccesscard'],
];
