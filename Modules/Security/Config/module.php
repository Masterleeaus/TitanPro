<?php

return [
    'name' => 'Security',
    'alias' => 'security',
    'version' => '1.2.0',
    'description' => 'Unified security operations module for goods in/out validation, work permits, work permit files, and access cards.',
    'combined_modules' => ['Security', 'TrInOutPermit', 'TrWorkPermits', 'TrAccessCard'],
    'primary_provider' => Modules\Security\Providers\ModuleServiceProvider::class,
    'diagnostics_command' => 'security:health',
];
