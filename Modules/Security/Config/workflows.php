<?php

return [
    'goods_in_out' => [
        'states' => ['draft', 'submitted', 'approved', 'approved_bm', 'pending_validation', 'validated', 'rejected'],
        'approval_fields' => ['status_approve', 'status_approve_bm', 'status_validated'],
    ],
    'work_permit' => [
        'states' => ['draft', 'submitted', 'approved', 'approved_bm', 'pending_validation', 'validated', 'rejected'],
        'approval_fields' => ['status_approve', 'status_approve_bm', 'status_validated'],
    ],
    'access_card' => [
        'states' => ['draft', 'submitted', 'issued', 'closed'],
        'approval_fields' => ['status'],
    ],
];
