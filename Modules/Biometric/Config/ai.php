<?php

return [
    'tools' => [
        'detect_attendance_anomaly' => Modules\Biometric\AI\Tools\DetectAttendanceAnomalyTool::class,
        'predict_overtime_risk' => Modules\Biometric\AI\Tools\PredictOvertimeRiskTool::class,
    ],
    'default_risk_class' => 'medium',
];

