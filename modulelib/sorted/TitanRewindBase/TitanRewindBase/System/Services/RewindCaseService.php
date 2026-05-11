<?php

namespace App\Extensions\TitanRewind\System\Services;

use Illuminate\Support\Facades\DB;
use App\Extensions\TitanRewind\System\Models\RewindCase;

class RewindCaseService
{
    public function openCase(array $data): RewindCase
    {
        return DB::transaction(function () use ($data) {
            return RewindCase::query()->create([
                'company_id' => $data['company_id'],
                'user_id' => $data['user_id'],
                'title' => $data['title'] ?? 'Issue detected',
                'status' => $data['status'] ?? 'open',
                'severity' => $data['severity'] ?? 'medium',
                'source_type' => $data['source_type'] ?? null,
                'source_id' => $data['source_id'] ?? null,
                'detected_at' => $data['detected_at'] ?? now(),
                'meta_json' => $data['meta_json'] ?? [],
            ]);
        });
    }

    public function resolveCase(RewindCase $case, array $actor): RewindCase
    {
        $case->status = 'resolved';
        $case->resolved_at = now();
        $case->resolved_by_type = $actor['type'] ?? 'user';
        $case->resolved_by_id = $actor['id'] ?? null;
        $case->save();
        return $case;
    }
}
