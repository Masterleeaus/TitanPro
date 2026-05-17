<?php

return [
    'capabilities' => [
        'payroll.calculation',
        'payroll.run.preview',
        'payroll.run.generate',
        'payroll.workflow.approval',
        'payroll.ai.anomaly_detection',
    ],
    'dependencies' => [
        'attendance' => 'optional',
        'timesheet' => 'not_required',
        'expenses' => 'optional',
    ],
];
