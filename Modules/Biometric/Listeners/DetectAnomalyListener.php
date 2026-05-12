<?php

namespace Modules\Biometric\Listeners;

use Modules\Biometric\AI\Tools\DetectAttendanceAnomalyTool;
use Modules\Biometric\AI\Tools\PredictOvertimeRiskTool;
use Modules\Biometric\Events\AnomalyDetected;
use Modules\Biometric\Events\AttendanceRecorded;
use Modules\Biometric\Events\OvertimeThresholdReached;

class DetectAnomalyListener
{
    private const DEFAULT_LATE_MINUTES_ON_CLOCK_OUT = 5;
    private const DEFAULT_SCHEDULED_HOURS = 8.0;

    public function __construct(
        private readonly DetectAttendanceAnomalyTool $anomalyTool,
        private readonly PredictOvertimeRiskTool $overtimeTool,
    ) {}

    public function handle(AttendanceRecorded $event): void
    {
        $anomaly = $this->anomalyTool->execute([
            'employee_id' => (int) $event->employeeId,
            'late_minutes' => $event->clockIn ? 0 : self::DEFAULT_LATE_MINUTES_ON_CLOCK_OUT,
        ]);

        if (($anomaly['anomaly_type'] ?? 'none') !== 'none') {
            event(new AnomalyDetected(
                companyId: $event->companyId,
                employeeId: $event->employeeId,
                anomalyType: (string) $anomaly['anomaly_type'],
                confidence: (float) $anomaly['confidence'],
            ));
        }

        $overtime = $this->overtimeTool->execute([
            'worked_hours' => $event->workedHours,
            'scheduled_hours' => self::DEFAULT_SCHEDULED_HOURS,
        ]);

        if (($overtime['risk_level'] ?? 'low') !== 'low') {
            event(new OvertimeThresholdReached(
                companyId: $event->companyId,
                employeeId: $event->employeeId,
                projectedHours: (float) $overtime['projected_hours'],
                riskLevel: (string) $overtime['risk_level'],
            ));
        }
    }
}
