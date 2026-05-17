<?php

return [
    'payroll_run' => [
        'states' => ['draft', 'pending_approval', 'approved', 'rejected', 'paid', 'cancelled'],
        'transitions' => [
            'submit' => ['from' => ['draft'], 'to' => 'pending_approval'],
            'approve' => ['from' => ['pending_approval'], 'to' => 'approved'],
            'reject' => ['from' => ['pending_approval'], 'to' => 'rejected'],
            'mark_paid' => ['from' => ['approved'], 'to' => 'paid'],
            'cancel' => ['from' => ['draft', 'pending_approval'], 'to' => 'cancelled'],
        ],
    ],
];
