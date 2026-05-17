<?php

declare(strict_types=1);

return [
    'name' => 'Budgeting',
    'version' => '2.2',

    'features' => [
        'expenses' => true,
        'receipts' => true,
        'reimbursements' => true,
        'actuals' => true,
        'variance' => true,
        'forecasting' => true,
        'kpi_snapshots' => true,
        'approval_thresholds' => true,
        'integrations' => true,
    ],

    'filament_panel' => 'budgeting',

    'currency_default' => 'AUD',

    'pagination' => [
        'per_page' => 25,
    ],
];
