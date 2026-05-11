<?php

namespace App\Extensions\TitanPulse\Automation\Actions;

use Illuminate\Support\Facades\DB;

class RunAnalysisAction
{
    public static function run(object $signal, array $payload, array $params): array
    {
        $teamId = (int)$signal->team_id;
        $companyId = (int)($signal->company_id ?? $teamId);
        $userId = $signal->user_id ?? null;

        $analysisType = (string)($params['analysis_type'] ?? 'work');
        $title = (string)($params['title'] ?? ('Analysis: ' . $analysisType));

        // MVP: store payload as analysis result; real AI analysis can be plugged in later.
        $result = [
            'signal_id' => $signal->id ?? null,
            'event' => property_exists($signal, 'signal_type') ? $signal->signal_type : ($signal->type ?? null),
            'payload' => $payload,
            'note' => $params['note'] ?? 'MVP analysis stored; plug AI later.',
        ];

        $id = DB::table('tz_analyses')->insertGetId([
            'team_id' => $teamId,
            'company_id' => $companyId,
            'user_id' => $userId,
            'analysis_type' => $analysisType,
            'title' => $title,
            'summary' => $params['summary'] ?? null,
            'result_json' => json_encode($result, JSON_UNESCAPED_UNICODE),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return ['action' => 'run_analysis', 'status' => 'ok', 'analysis_id' => $id];
    }
}
