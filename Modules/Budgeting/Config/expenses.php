<?php

declare(strict_types=1);

return [
    'approval_threshold' => 500.00,

    'receipt_required' => true,

    'ocr_enabled' => true,

    'reimbursement_export_formats' => ['csv', 'xlsx', 'pdf'],

    'statuses' => ['draft', 'pending', 'approved', 'rejected', 'reimbursed'],

    'auto_submit_threshold' => null,
];
