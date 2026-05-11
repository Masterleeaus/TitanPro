<?php

namespace App\Extensions\TitanPulse\Services\SignalBus;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * DB-backed signal emitter.
 *
 * Writes into core tz_signals table (append-only).
 * Designed to tolerate small schema differences between builds by mapping to existing columns.
 */
class SignalEmitter
{
    /**
     * Emit a tenant-scoped signal.
     *
     * @param string $event e.g. work.job.completed
     * @param string|null $subjectType e.g. tz_job
     * @param int|null $subjectId
     * @param array $payload
     * @param array $opts Optional: team_id, user_id, company_id, idempotency_key, severity, title, body
     */
    public static function emit(string $event, ?string $subjectType = null, ?int $subjectId = null, array $payload = [], array $opts = []): int
    {
        $user = Auth::user();
        $teamId = $opts['team_id'] ?? ($user->team_id ?? null);
        if (!$teamId) {
            throw new \RuntimeException('SignalEmitter: team_id is required');
        }

        $companyId = $opts['company_id'] ?? $teamId;
        $userId = $opts['user_id'] ?? ($user->id ?? null);

        $idempotencyKey = $opts['idempotency_key'] ?? ($opts['dedupe_key'] ?? null);

        $columns = self::signalColumns();

        $row = [];
        // tenant
        if (isset($columns['team_id'])) $row['team_id'] = $teamId;
        if (isset($columns['company_id'])) $row['company_id'] = $companyId;
        if (isset($columns['user_id'])) $row['user_id'] = $userId;

        // type/source mappings
        if (isset($columns['source'])) {
            $row['source'] = $opts['source'] ?? 'assistant.lifecycle';
        }
        if (isset($columns['type'])) {
            $row['type'] = $opts['type'] ?? $event;
        }
        if (isset($columns['signal_type'])) {
            $row['signal_type'] = $event;
        }

        // subject/entity mappings
        if (isset($columns['subject_type'])) $row['subject_type'] = $subjectType;
        if (isset($columns['subject_id'])) $row['subject_id'] = $subjectId;
        if (isset($columns['entity_type'])) $row['entity_type'] = $subjectType;
        if (isset($columns['entity_id'])) $row['entity_id'] = $subjectId;

        // payload mappings
        $payloadJson = empty($payload) ? null : json_encode($payload, JSON_UNESCAPED_UNICODE);
        if (isset($columns['payload_json'])) $row['payload_json'] = $payloadJson;
        if (isset($columns['action_payload'])) $row['action_payload'] = $payload;
        if (isset($columns['payload'])) $row['payload'] = $payloadJson;
        if (isset($columns['payload_json']) && $payloadJson === null) $row['payload_json'] = null;

        // dedupe/idempotency
        if ($idempotencyKey) {
            if (isset($columns['idempotency_key'])) $row['idempotency_key'] = $idempotencyKey;
            if (isset($columns['dedupe_key'])) $row['dedupe_key'] = $idempotencyKey;
        }

        // optional UI fields if present
        if (isset($columns['severity']) && array_key_exists('severity', $opts)) $row['severity'] = (int)$opts['severity'];
        if (isset($columns['title'])) $row['title'] = $opts['title'] ?? $event;
        if (isset($columns['body'])) $row['body'] = $opts['body'] ?? null;

        // status if table supports
        if (isset($columns['status'])) {
            $row['status'] = $opts['status'] ?? 'open';
        }

        // timestamps
        if (isset($columns['created_at'])) $row['created_at'] = now();
        if (isset($columns['updated_at'])) $row['updated_at'] = now();

        // Append-only rule: do NOT upsert here. Consumers handle idempotency.
        return (int) DB::table('tz_signals')->insertGetId($row);
    }

    private static function signalColumns(): array
    {
        static $cached = null;
        if ($cached !== null) return $cached;

        $cols = [];
        try {
            foreach (Schema::getColumnListing('tz_signals') as $c) {
                $cols[$c] = true;
            }
        } catch (\Throwable $e) {
            // Table missing: fail loudly for now.
            throw new \RuntimeException('tz_signals table not found. Install Titan Signal core first.');
        }

        return $cached = $cols;
    }
}
