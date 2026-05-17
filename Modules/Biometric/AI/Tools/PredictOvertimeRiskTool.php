<?php

namespace Modules\Biometric\AI\Tools;

class PredictOvertimeRiskTool
{
    private const HIGH_RISK_THRESHOLD = 12.0;
    private const MEDIUM_RISK_THRESHOLD = 9.5;

    /**
     * @param  array{worked_hours?:float,scheduled_hours?:float}  $input
     * @return array{risk_level:string, projected_hours:float}
     */
    public function execute(array $input): array
    {
        $worked = (float) ($input['worked_hours'] ?? 0.0);
        $scheduled = max(1.0, (float) ($input['scheduled_hours'] ?? 8.0));
        $projected = max($worked, $scheduled + (($worked - $scheduled) * 0.5));

        $riskLevel = match (true) {
            $projected >= self::HIGH_RISK_THRESHOLD => 'high',
            $projected >= self::MEDIUM_RISK_THRESHOLD => 'medium',
            default => 'low',
        };

        return [
            'risk_level' => $riskLevel,
            'projected_hours' => round($projected, 2),
        ];
    }
}
