<?php

namespace Modules\Biometric\AI\Tools;

class PredictOvertimeRiskTool
{
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
            $projected >= 12.0 => 'high',
            $projected >= 9.5 => 'medium',
            default => 'low',
        };

        return [
            'risk_level' => $riskLevel,
            'projected_hours' => round($projected, 2),
        ];
    }
}

