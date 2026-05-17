<?php

namespace App\Extensions\TitanPulse\Automation\Actions;

use Illuminate\Support\Facades\DB;

class CreateSuggestionAction
{
    public static function run(object $signal, array $payload, array $params): array
    {
        $teamId = (int)$signal->team_id;
        $companyId = (int)($signal->company_id ?? $teamId);
        $userId = $signal->user_id ?? null;

        $title = (string)($params['title'] ?? ('Suggestion for ' . (property_exists($signal, 'signal_type') ? $signal->signal_type : ($signal->type ?? 'signal'))));
        $body = $params['body'] ?? null;
        $severity = (int)($params['severity'] ?? 0);
        $suggestionType = (string)($params['suggestion_type'] ?? 'work');

        $subjectType = $params['subject_type'] ?? (property_exists($signal, 'entity_type') ? $signal->entity_type : ($signal->subject_type ?? null));
        $subjectId = $params['subject_id'] ?? (property_exists($signal, 'entity_id') ? $signal->entity_id : ($signal->subject_id ?? null));

        $payloadJson = $params['payload'] ?? $payload;

        $id = DB::table('tz_ai_suggestions')->insertGetId([
            'team_id' => $teamId,
            'company_id' => $companyId,
            'user_id' => $userId,
            'suggestion_type' => $suggestionType,
            'severity' => $severity,
            'title' => $title,
            'body' => $body,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'payload_json' => json_encode($payloadJson, JSON_UNESCAPED_UNICODE),
            'status' => 'open',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['action' => 'create_suggestion', 'status' => 'ok', 'suggestion_id' => $id];
    }
}
