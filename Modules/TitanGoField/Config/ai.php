<?php

return [
    'tools' => [
        'create_field_job' => [
            'risk_class'    => 'low',
            'approval_mode' => 'auto',
        ],
        'update_field_job_status' => [
            'risk_class'    => 'medium',
            'approval_mode' => 'confirm',
        ],
        'complete_field_job' => [
            'risk_class'    => 'medium',
            'approval_mode' => 'confirm',
        ],
        'get_field_job_summary' => [
            'risk_class'    => 'low',
            'approval_mode' => 'auto',
        ],
    ],
];
