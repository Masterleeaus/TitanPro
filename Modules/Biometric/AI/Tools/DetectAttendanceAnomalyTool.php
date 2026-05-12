<?php

namespace Modules\Biometric\AI\Tools;

class DetectAttendanceAnomalyTool
{
    /**
     * @param  array{employee_id:int,late_minutes?:int,location_delta_meters?:float}  $input
     * @return array{anomaly_type:string, employee_id:int, confidence:float}
     */
    public function execute(array $input): array
    {
        $employeeId = (int) ($input['employee_id'] ?? 0);
        $lateMinutes = (int) ($input['late_minutes'] ?? 0);
        $locationDelta = (float) ($input['location_delta_meters'] ?? 0.0);

        if ($lateMinutes >= 30) {
            return [
                'anomaly_type' => 'severe_late_arrival',
                'employee_id' => $employeeId,
                'confidence' => 0.92,
            ];
        }

        if ($lateMinutes >= 10 || $locationDelta >= 150.0) {
            return [
                'anomaly_type' => 'attendance_outlier',
                'employee_id' => $employeeId,
                'confidence' => 0.74,
            ];
        }

        return [
            'anomaly_type' => 'none',
            'employee_id' => $employeeId,
            'confidence' => 0.10,
        ];
    }
}

