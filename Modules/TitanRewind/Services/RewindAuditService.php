<?php

namespace Modules\TitanRewind\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\TitanRewind\Models\RewindEvent;

class RewindAuditService
{
    public function __construct(
        private readonly RewindCaseService $cases,
        private readonly RewindSuggestionService $suggestions,
    ) {}

    public function appendEvent(array $data): RewindEvent
    {
        $case = $data['case'] ?? $this->cases->findOrOpenCase([
            'company_id' => $data['company_id'],
            'case_key' => $data['case_key'] ?? null,
            'title' => $data['title'] ?? 'Tracked mutation detected',
            'severity' => $data['severity'] ?? 'medium',
            'source_type' => $data['source_type'] ?? 'audit',
            'source_id' => $data['source_id'] ?? null,
            'entity_type' => $data['entity_type'] ?? null,
            'entity_id' => $data['entity_id'] ?? null,
            'detected_at' => $data['created_at'] ?? now(),
            'meta_json' => $data['case_meta_json'] ?? [],
        ]);

        $data['created_at'] = $data['created_at'] ?? now();

        return DB::transaction(function () use ($case, $data) {
            $idempotencyKey = $data['idempotency_key'] ?? (string) Str::uuid();

            $existing = RewindEvent::query()
                ->where('company_id', $data['company_id'])
                ->where('case_id', $case->id)
                ->where('idempotency_key', $idempotencyKey)
                ->first();

            if ($existing) {
                return $existing;
            }

            $previous = RewindEvent::query()
                ->where('company_id', $data['company_id'])
                ->where('case_id', $case->id)
                ->latest('id')
                ->first();

            $payload = [
                'company_id' => $data['company_id'],
                'case_id' => $case->id,
                'event_type' => $data['event_type'] ?? 'updated',
                'entity_type' => $data['entity_type'] ?? null,
                'entity_id' => $data['entity_id'] ?? null,
                'actor_type' => $data['actor_type'] ?? 'system',
                'actor_id' => $data['actor_id'] ?? null,
                'idempotency_key' => $idempotencyKey,
                'payload_json' => $data['payload_json'] ?? [],
                'prev_event_hash' => $previous?->event_hash,
                'created_at' => $data['created_at'],
            ];

            $payload['event_hash'] = hash('sha256', json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

            $event = RewindEvent::query()->create($payload);

            $this->suggestions->suggestForEvent($event);

            return $event;
        });
    }
}
