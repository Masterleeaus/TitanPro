<?php

namespace App\Extensions\TitanPulse\Automation\Actions;

use Illuminate\Support\Facades\DB;

class QueuePendingAction
{
    public static function run(object $signal, array $payload, array $params): array
    {
        $teamId = (int)$signal->team_id;
        $companyId = (int)($signal->company_id ?? $teamId);
        $userId = $signal->user_id ?? null;

        $actionType = (string)($params['action_type'] ?? 'generic');
        $title = (string)($params['title'] ?? ('Pending: ' . $actionType));
        $body = $params['body'] ?? null;

        $subjectType = $params['subject_type'] ?? (property_exists($signal, 'entity_type') ? $signal->entity_type : ($signal->subject_type ?? null));
        $subjectId = $params['subject_id'] ?? (property_exists($signal, 'entity_id') ? $signal->entity_id : ($signal->subject_id ?? null));

        $payloadJson = $params['payload'] ?? $payload;

        $id = DB::table('tz_pending_actions')->insertGetId([
            'team_id' => $teamId,
            'company_id' => $companyId,
            'user_id' => $userId,
            'action_type' => $actionType,
            'title' => $title,
            'body' => $body,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'payload_json' => json_encode($payloadJson, JSON_UNESCAPED_UNICODE),
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['action' => 'queue_pending_action', 'status' => 'ok', 'pending_action_id' => $id];
    }
}
