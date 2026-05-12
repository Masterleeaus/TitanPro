<?php

namespace Modules\TitanRewind\AI\Tools;

use Modules\TitanRewind\Models\RewindEvent;

class AnalyseAuditDriftTool
{
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

        $confidence = count($anomalies) === 0 ? 0.25 : min(0.99, 0.5 + (count($anomalies) * 0.1));

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
