<?php

namespace Modules\TitanRewind\Services;

use Modules\TitanRewind\Models\RewindCase;

class RewindCaseService
{
    public function openCase(array $data): RewindCase
    {
        return RewindCase::query()->create([
            'company_id' => $data['company_id'],
            'case_key' => $data['case_key'] ?? $this->makeCaseKey(
                $data['entity_type'] ?? 'entity',
                $data['entity_id'] ?? 'unknown',
            ),
            'title' => $data['title'] ?? 'Rewind case opened',
            'status' => $data['status'] ?? 'open',
            'severity' => $data['severity'] ?? 'medium',
            'source_type' => $data['source_type'] ?? 'audit',
            'source_id' => $data['source_id'] ?? null,
            'entity_type' => $data['entity_type'] ?? null,
            'entity_id' => $data['entity_id'] ?? null,
            'detected_at' => $data['detected_at'] ?? now(),
            'meta_json' => $data['meta_json'] ?? [],
        ]);
    }

    public function findOrOpenCase(array $data): RewindCase
    {
        return RewindCase::query()->firstOrCreate(
            [
                'company_id' => $data['company_id'],
                'case_key' => $data['case_key'] ?? $this->makeCaseKey(
                    $data['entity_type'] ?? 'entity',
                    $data['entity_id'] ?? 'unknown',
                ),
                'status' => 'open',
            ],
            [
                'title' => $data['title'] ?? 'Rewind case opened',
                'severity' => $data['severity'] ?? 'medium',
                'source_type' => $data['source_type'] ?? 'audit',
                'source_id' => $data['source_id'] ?? null,
                'entity_type' => $data['entity_type'] ?? null,
                'entity_id' => $data['entity_id'] ?? null,
                'detected_at' => $data['detected_at'] ?? now(),
                'meta_json' => $data['meta_json'] ?? [],
            ],
        );
    }

    public function closeCase(RewindCase $case, array $actor = []): RewindCase
    {
        $case->forceFill([
            'status' => 'closed',
            'resolved_at' => now(),
            'resolved_by_type' => $actor['type'] ?? 'user',
            'resolved_by_id' => $actor['id'] ?? null,
        ])->save();

        return $case->refresh();
    }

    private function makeCaseKey(string $entityType, string|int|null $entityId): string
    {
        return sprintf('%s:%s', $entityType, (string) $entityId);
    }
}
