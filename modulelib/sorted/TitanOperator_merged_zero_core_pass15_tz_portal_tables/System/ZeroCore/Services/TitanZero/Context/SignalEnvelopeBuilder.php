<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Context;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class SignalEnvelopeBuilder
{
    public function build(?int $teamId = null, ?int $userId = null): array
    {
        $user = Auth::user();
        $teamId ??= $user?->team_id;
        $userId ??= $user?->id;

        $sourceMap = config('titan_operator.zero.signal_sources', []);

        return [
            'identity' => [
                'user_id' => $userId,
                'team_id' => $teamId,
                'company_id' => $teamId,
                'user_name' => $user?->name,
            ],
            'governance' => [
                'proposal_count' => $this->countForTeam(config('titan_operator.zero.proposal_table'), $teamId),
                'action_count' => $this->countForTeam(config('titan_operator.zero.proposal_action_table'), $teamId),
                'decision_count' => $this->countForTeam(config('titan_operator.zero.decision_table'), $teamId),
                'audit_count' => $this->countForTeam(config('titan_operator.zero.audit_table'), $teamId),
                'rewind_count' => $this->countForTeam(config('titan_operator.zero.rewind_table'), $teamId),
                'recent_proposals' => $this->recentRows(config('titan_operator.zero.proposal_table'), $teamId, ['id', 'intent', 'risk', 'status', 'updated_at']),
                'recent_audit' => $this->recentRows(config('titan_operator.zero.audit_table'), $teamId, ['id', 'event_key', 'created_at']),
            ],
            'memory' => [
                'session_count' => $this->countForTeam(data_get(config('titan_operator.zero.memory_tables'), 'sessions'), $teamId),
                'snapshot_count' => $this->countForTeam(data_get(config('titan_operator.zero.memory_tables'), 'context_snapshots'), $teamId),
                'learning_delta_count' => $this->countForTeam(data_get(config('titan_operator.zero.memory_tables'), 'learning_deltas'), $teamId),
                'site_memory_count' => $this->countForTeam(data_get(config('titan_operator.zero.memory_tables'), 'site_memory'), $teamId),
                'job_memory_count' => $this->countForTeam(data_get(config('titan_operator.zero.memory_tables'), 'job_memory'), $teamId),
            ],
            'signals' => [
                'source_map' => $sourceMap,
                'stage_model' => config('titan_operator.zero.signal_stage_model', []),
                'tz_table_count' => $this->countConfiguredTables($sourceMap, $teamId),
                'table_counts' => $this->configuredTableCounts($sourceMap, $teamId),
                'generated_at' => now()->toIso8601String(),
            ],
        ];
    }

    protected function configuredTableCounts(array $sourceMap, ?int $teamId): array
    {
        return collect($sourceMap)
            ->map(function ($tables, $group) use ($teamId) {
                return [
                    'group' => $group,
                    'tables' => collect($tables)
                        ->filter(fn ($table) => is_string($table) && Str::startsWith($table, 'tz_'))
                        ->unique()
                        ->mapWithKeys(fn ($table) => [$table => $this->countForTeam($table, $teamId)])
                        ->all(),
                ];
            })
            ->values()
            ->all();
    }

    protected function countConfiguredTables(array $sourceMap, ?int $teamId): int
    {
        $tables = collect($sourceMap)
            ->flatten()
            ->filter(fn ($table) => is_string($table) && Str::startsWith($table, 'tz_'))
            ->unique();

        return (int) $tables->sum(fn ($table) => $this->countForTeam($table, $teamId));
    }

    protected function recentRows(?string $table, ?int $teamId, array $columns): array
    {
        if (! $this->isTitanTable($table) || ! Schema::hasTable($table)) {
            return [];
        }

        $query = DB::table($table)->orderByDesc('id')->limit(5);

        if ($teamId && Schema::hasColumn($table, 'team_id')) {
            $query->where('team_id', $teamId);
        }

        $select = collect($columns)
            ->filter(fn ($column) => Schema::hasColumn($table, $column))
            ->values()
            ->all();

        if ($select === []) {
            $select = ['id'];
        }

        return $query->get($select)
            ->map(function ($row) {
                $data = (array) $row;

                foreach ($data as $key => $value) {
                    if ($value instanceof \DateTimeInterface) {
                        $data[$key] = $value->format('Y-m-d H:i:s');
                    }
                }

                return $data;
            })
            ->all();
    }

    protected function countForTeam(?string $table, ?int $teamId): int
    {
        if (! $this->isTitanTable($table) || ! Schema::hasTable($table)) {
            return 0;
        }

        $query = DB::table($table);

        if ($teamId && Schema::hasColumn($table, 'team_id')) {
            $query->where('team_id', $teamId);
        }

        return (int) $query->count();
    }

    protected function isTitanTable(?string $table): bool
    {
        return is_string($table) && Str::startsWith($table, 'tz_');
    }
}
