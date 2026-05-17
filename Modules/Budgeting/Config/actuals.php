<?php

declare(strict_types=1);

return [
    'sync_sources' => ['accounting', 'banking', 'payroll', 'erp'],

    'lock_periods' => true,

    'lock_after_days' => 30,

    'auto_reconcile' => false,

    'variance_warning_pct' => 10.0,

    'variance_critical_pct' => 25.0,
];
