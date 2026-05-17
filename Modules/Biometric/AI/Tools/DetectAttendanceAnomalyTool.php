<?php

namespace Modules\Biometric\AI\Tools;

class DetectAttendanceAnomalyTool
{
    private const SEVERE_LATE_MINUTES = 30;
    private const STANDARD_LATE_MINUTES = 10;
    private const LOCATION_DELTA_METERS = 150.0;
    private const SEVERE_CONFIDENCE = 0.92;
    private const STANDARD_CONFIDENCE = 0.74;
    private const NO_ANOMALY_CONFIDENCE = 0.10;

    /**
     * @param  array{employee_id:int,late_minutes?:int,location_delta_meters?:float}  $input
     * @return array{anomaly_type:string, employee_id:int, confidence:float}
     */
    public function execute(array $input): array
    {
        $employeeId = (int) ($input['employee_id'] ?? 0);
        $lateMinutes = (int) ($input['late_minutes'] ?? 0);
        $locationDelta = (float) ($input['location_delta_meters'] ?? 0.0);

        if ($lateMinutes >= self::SEVERE_LATE_MINUTES) {
            return [
                'anomaly_type' => 'severe_late_arrival',
                'employee_id' => $employeeId,
                'confidence' => self::SEVERE_CONFIDENCE,
            ];
        }

        if ($lateMinutes >= self::STANDARD_LATE_MINUTES || $locationDelta >= self::LOCATION_DELTA_METERS) {
            return [
                'anomaly_type' => 'attendance_outlier',
                'employee_id' => $employeeId,
                'confidence' => self::STANDARD_CONFIDENCE,
            ];
        }

        return [
            'anomaly_type' => 'none',
            'employee_id' => $employeeId,
            'confidence' => self::NO_ANOMALY_CONFIDENCE,
        ];
    }
}
