<?php

namespace App\Extensions\TitanRewind\System\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Extensions\TitanRewind\System\Models\RewindEvent;

class RewindAuditService
{
    public function appendEvent(array $data): RewindEvent
    {
        $data['created_at'] = $data['created_at'] ?? now();

        if (!empty($data['idempotency_key'])) {
            $existing = RewindEvent::query()
                ->where('company_id', $data['company_id'])
                ->where('user_id', $data['user_id'])
                ->where('idempotency_key', $data['idempotency_key'])
                ->first();
            if ($existing) return $existing;
        }

        return DB::transaction(function () use ($data) {
            $prev = RewindEvent::query()
                ->where('company_id', $data['company_id'])
                ->where('user_id', $data['user_id'])
                ->where('case_id', $data['case_id'])
                ->orderByDesc('id')
                ->first();

            $prevHash = $prev?->event_hash;

            $payload = [
                'company_id' => $data['company_id'],
                'user_id' => $data['user_id'],
                'case_id' => $data['case_id'],
                'event_type' => $data['event_type'] ?? 'event',
                'entity_type' => $data['entity_type'] ?? null,
                'entity_id' => $data['entity_id'] ?? null,
                'actor_type' => $data['actor_type'] ?? 'system',
                'actor_id' => $data['actor_id'] ?? null,
                'idempotency_key' => $data['idempotency_key'] ?? (string)Str::uuid(),
                'payload_json' => $data['payload_json'] ?? [],
                'prev_event_hash' => $prevHash,
                'created_at' => $data['created_at'],
            ];

            $payload['event_hash'] = hash('sha256', json_encode($payload, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE));
            return RewindEvent::query()->create($payload);
        });
    }
}
