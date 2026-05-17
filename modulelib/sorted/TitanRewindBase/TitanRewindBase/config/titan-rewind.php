<?php

return [
    // enable scheduled queue processing
    'scheduler_enabled' => true,

    // default per-run processing limit
    'process_limit' => 50,

    // allowlisted fix types (expand carefully)
    'allowlisted_fix_types' => [
        'metadata_update',
    ],

    // allowlisted target tables for metadata_update (template defaults only)
    'allowlisted_target_tables' => [
        'boxed_automation_cases',
    ],
];
