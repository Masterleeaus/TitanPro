<?php

namespace Modules\TitanRewind\AI\Tools;

use Modules\TitanRewind\Models\RewindEvent;

class AnalyseAuditDriftTool
{
    private const BASE_CONFIDENCE = 0.25;
    private const CONFIDENCE_FLOOR = 0.5;
    private const CONFIDENCE_STEP = 0.1;
    private const CONFIDENCE_MAX = 0.99;

    /** @return array{anomalies: array<int, array<string, mixed>>, confidence: float} */
    public function execute(array $input): array
    {
        $companyId = (int) ($input['company_id'] ?? 0);

        $query = RewindEvent::query()->where('company_id', $companyId);

        if (isset($input['case_id'])) {
            $query->where('case_id', (int) $input['case_id']);
        }

        $rows = $query
            ->selectRaw('event_type, entity_type, count(*) as total')
            ->groupBy('event_type', 'entity_type')
            ->havingRaw('count(*) > 1')
            ->get();

        $anomalies = $rows->map(static fn ($row): array => [
            'event_type' => $row->event_type,
            'entity_type' => $row->entity_type,
            'count' => (int) $row->total,
        ])->values()->all();

        $anomalyCount = count($anomalies);

        $confidence = $anomalyCount === 0
            ? self::BASE_CONFIDENCE
            : min(self::CONFIDENCE_MAX, self::CONFIDENCE_FLOOR + ($anomalyCount * self::CONFIDENCE_STEP));

        return [
            'anomalies' => $anomalies,
            'confidence' => (float) $confidence,
        ];
    }

    /** @return array{anomalies: array<int, array<string, mixed>>, confidence: float} */
    public function __invoke(array $input): array
    {
        return $this->execute($input);
    }
}
