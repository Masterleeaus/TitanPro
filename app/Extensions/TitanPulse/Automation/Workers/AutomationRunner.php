<?php

namespace App\Extensions\TitanPulse\Automation\Workers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AutomationRunner
{

    /**
     * Run scheduled sweeps + consume tz_signals. Intended to be called frequently (eg every 5 minutes).
     */
    public function run(int $limit = 200, ?int $teamId = null, bool $runSweeps = true, bool $runSignals = true): array
    {
        $out = [
            'sweeps' => null,
            'signals' => null,
        ];

        if ($runSweeps) {
            $out['sweeps'] = $this->runSweeps($teamId);
        }

        if ($runSignals) {
            $out['signals'] = $this->runSignals($limit, $teamId);
        }

        return $out;
    }

    public function runSignals(int $limit = 200, ?int $teamId = null): array
    {
        $signalCols = $this->cols('tz_signals');
        $runsCols = $this->cols('tz_automation_runs');

        $signalEventCol = isset($signalCols['signal_type']) ? 'signal_type' : (isset($signalCols['type']) ? 'type' : null);
        if (!$signalEventCol) {
            throw new \RuntimeException('tz_signals missing signal_type/type column');
        }

        $idempoCol = isset($signalCols['idempotency_key']) ? 'idempotency_key' : (isset($signalCols['dedupe_key']) ? 'dedupe_key' : null);

        $q = DB::table('tz_signals as s')
            ->select('s.*')
            ->whereNotNull('s.team_id')
            ->orderBy('s.id', 'asc');

        // Prefer open/pending signals if status exists
        if (isset($signalCols['status'])) {
            // Accept both Titan Signal statuses and the extension's open/ack/done.
            $q->whereIn('s.status', ['pending', 'open']);
        }

        if ($teamId) {
            $q->where('s.team_id', $teamId);
        }

        // Skip already processed signals for this agent
        $q->leftJoin('tz_automation_runs as r', function ($join) {
            $join->on('r.signal_id', '=', 's.id')
                 ->where('r.agent_type', '=', 'titan_work_automation');
        })->whereNull('r.id');

        $signals = $q->limit($limit)->get();

        $processed = 0;
        $failed = 0;
        $skipped = 0;

        $engine = new \App\Extensions\TitanPulse\Automation\Rules\RuleEngine();

        foreach ($signals as $signal) {
            $event = $signal->{$signalEventCol};
            $team = (int)$signal->team_id;
            $idempo = $idempoCol ? ($signal->{$idempoCol} ?? null) : null;

            // Second layer idempotency: if idempotency key already logged, skip
            if ($idempo) {
                $existing = DB::table('tz_automation_runs')
                    ->where('team_id', $team)
                    ->where('agent_type', 'titan_work_automation')
                    ->where('idempotency_key', $idempo)
                    ->exists();
                if ($existing) {
                    $this->logRun($team, (int)($signal->company_id ?? $team), (int)($signal->user_id ?? 0) ?: null, $signal->id, $idempo, 'skipped', ['reason' => 'idempotency_key already processed'], null);
                    $skipped++;
                    continue;
                }
            }

            try {
                $result = $engine->handleSignal($signal);
                $this->logRun($team, (int)($signal->company_id ?? $team), (int)($signal->user_id ?? 0) ?: null, $signal->id, $idempo, 'ok', $result, null);
                $processed++;
            } catch (\Throwable $e) {
                $this->logRun($team, (int)($signal->company_id ?? $team), (int)($signal->user_id ?? 0) ?: null, $signal->id, $idempo, 'failed', null, $e->getMessage());
                $failed++;
            }
        }

        return [
            'processed' => $processed,
            'failed' => $failed,
            'skipped' => $skipped,
            'scanned' => count($signals),
        ];
    }

    
    public function runSweeps(?int $teamId = null): array
    {
        $tz = config('app.timezone') ?: 'UTC';
        $now = Carbon::now($tz);

        $engine = new \App\Extensions\TitanPulse\Automation\Rules\RuleEngine();

        // Determine which tenants to sweep
        if ($teamId) {
            $teamIds = [$teamId];
        } else {
            $teamIds = DB::table('tz_automation_rules')
                ->where('enabled', 1)
                ->where('trigger_type', 'schedule')
                ->distinct()
                ->pluck('team_id')
                ->map(fn ($v) => (int)$v)
                ->toArray();
        }

        $jobsTable = $this->detectJobsTable();

        $ran = 0;
        $skipped = 0;
        $failed = 0;

        foreach ($teamIds as $tid) {
            if (!$tid) continue;

            $rules = DB::table('tz_automation_rules')
                ->where('team_id', $tid)
                ->where('enabled', 1)
                ->where('trigger_type', 'schedule')
                ->orderBy('id', 'asc')
                ->get();

            foreach ($rules as $rule) {
                $event = (string)$rule->trigger_event;
                if (!$this->scheduleDue($event, $now)) {
                    continue;
                }

                try {
                    $subjects = $this->subjectsForScheduleRule($event, $tid, $now, $jobsTable);

                    foreach ($subjects as $subj) {
                        $subjectType = $subj['subject_type'] ?? null;
                        $subjectId = $subj['subject_id'] ?? null;
                        $payload = $subj['payload'] ?? [];

                        $idempo = $this->buildSweepIdempotencyKey($tid, (int)$rule->id, $subjectId, $event, $now);

                        $exists = DB::table('tz_automation_runs')
                            ->where('team_id', $tid)
                            ->where('agent_type', 'titan_work_automation')
                            ->where('idempotency_key', $idempo)
                            ->exists();
                        if ($exists) {
                            $skipped++;
                            continue;
                        }

                        $result = $engine->handleScheduleEvent(
                            $tid,
                            (int)($rule->company_id ?? $tid),
                            null,
                            $event,
                            $payload,
                            $subjectType,
                            $subjectId
                        );

                        $this->logRun($tid, (int)($rule->company_id ?? $tid), null, null, $idempo, 'ok', array_merge(['rule_id' => (int)$rule->id], $result), null);
                        $ran++;
                    }
                } catch (\Throwable $e) {
                    $this->logRun($tid, (int)($rule->company_id ?? $tid), null, null, $this->buildSweepIdempotencyKey($tid, (int)$rule->id, null, $event, $now), 'failed', null, $e->getMessage());
                    $failed++;
                }
            }
        }

        return [
            'now' => $now->toDateTimeString(),
            'jobs_table' => $jobsTable,
            'ran' => $ran,
            'skipped' => $skipped,
            'failed' => $failed,
            'tenants' => count($teamIds),
        ];
    }

    private function scheduleDue(string $event, Carbon $now): bool
    {
        if (str_starts_with($event, 'schedule.daily.')) {
            $time = substr($event, strlen('schedule.daily.'));
            if (!preg_match('/^\d{2}:\d{2}$/', $time)) return false;
            [$hh, $mm] = array_map('intval', explode(':', $time));

            $target = $now->copy()->setTime($hh, $mm, 0);
            return $now->greaterThanOrEqualTo($target) && $now->lessThan($target->copy()->addMinutes(10));
        }

        if ($event === 'schedule.hourly') {
            return (int)$now->minute < 10;
        }

        if ($event === 'schedule.every_30_min') {
            return in_array((int)$now->minute, [0, 30], true);
        }

        if ($event === 'schedule.weekly') {
            // Default: Monday 02:00 local time, run within first 10 minutes.
            return (int)$now->dayOfWeekIso === 1 && (int)$now->hour === 2 && (int)$now->minute < 10;
        }

        return false;
    }

    private function buildSweepIdempotencyKey(int $teamId, int $ruleId, $subjectId, string $event, Carbon $now): string
    {
        $bucket = 'daily:' . $now->format('Y-m-d');
        if ($event === 'schedule.hourly' || $event === 'schedule.every_30_min') {
            $bucket = 'hour:' . $now->format('Y-m-d-H');
        }
        if ($event === 'schedule.weekly') {
            $bucket = 'week:' . $now->format('o-\\WW');
        }

        $sid = $subjectId !== null ? (string)$subjectId : 'team';
        return "company:{$teamId}|rule:{$ruleId}|subject:{$sid}|{$bucket}";
    }

    private function detectJobsTable(): ?string
    {
        $candidates = ['tz_work_jobs','tz_jobs','tr_jobs','work_jobs','jobs'];
        foreach ($candidates as $t) {
            if (Schema::hasTable($t)) return $t;
        }
        return null;
    }

    private function subjectsForScheduleRule(string $event, int $teamId, Carbon $now, ?string $jobsTable): array
    {
        if (!$jobsTable) {
            return [[
                'subject_type' => 'team',
                'subject_id' => $teamId,
                'payload' => ['warning' => 'jobs_table_missing', 'team_id' => $teamId],
            ]];
        }

        if ($event === 'schedule.daily.05:30') {
            $jobs = $this->queryJobsForTodayPlan($jobsTable, $teamId, $now);
            return [[
                'subject_type' => 'team',
                'subject_id' => $teamId,
                'payload' => ['team_id' => $teamId, 'date' => $now->toDateString(), 'jobs' => $jobs],
            ]];
        }

        if ($event === 'schedule.hourly') {
            $jobs = $this->queryJobsNeedingAccessInfo($jobsTable, $teamId, $now);
            return array_map(fn ($j) => ['subject_type' => 'job', 'subject_id' => (int)$j['id'], 'payload' => $j], $jobs);
        }

        if ($event === 'schedule.every_30_min') {
            $jobs = $this->queryJobsLateToStart($jobsTable, $teamId, $now);
            return array_map(fn ($j) => ['subject_type' => 'job', 'subject_id' => (int)$j['id'], 'payload' => $j], $jobs);
        }

        if ($event === 'schedule.daily.02:00') {
            $jobs = $this->queryRecurringDrift($jobsTable, $teamId, $now);
            return array_map(fn ($j) => ['subject_type' => 'job', 'subject_id' => (int)$j['id'], 'payload' => $j], $jobs);
        }

        if ($event === 'schedule.daily.06:00') {
            $jobs = $this->queryOverdueJobs($jobsTable, $teamId, $now);
            return [[
                'subject_type' => 'team',
                'subject_id' => $teamId,
                'payload' => ['team_id' => $teamId, 'date' => $now->toDateString(), 'overdue_jobs' => $jobs, 'overdue_count' => count($jobs)],
            ]];
        }

        if ($event === 'schedule.weekly') {
            $jobs = $this->queryAbsentClientPattern($jobsTable, $teamId, $now);
            return array_map(fn ($j) => ['subject_type' => 'job', 'subject_id' => (int)$j['id'], 'payload' => $j], $jobs);
        }

        return [];
    }

    private function queryJobsForTodayPlan(string $jobsTable, int $teamId, Carbon $now): array
    {
        $cols = $this->cols($jobsTable);
        $startCol = isset($cols['scheduled_start']) ? 'scheduled_start' : (isset($cols['start_at']) ? 'start_at' : (isset($cols['scheduled_at']) ? 'scheduled_at' : null));
        $statusCol = isset($cols['status']) ? 'status' : null;

        $q = DB::table($jobsTable)->where('team_id', $teamId);
        if ($startCol) {
            $q->whereBetween($startCol, [$now->copy()->startOfDay(), $now->copy()->endOfDay()]);
        }
        if ($statusCol) {
            $q->whereNotIn($statusCol, ['cancelled']);
        }

        return $q->orderBy($startCol ?: 'id', 'asc')->limit(50)->get()->map(function ($r) use ($startCol, $statusCol) {
            return [
                'id' => (int)($r->id ?? 0),
                'scheduled_start' => $startCol ? ($r->{$startCol} ?? null) : null,
                'status' => $statusCol ? ($r->{$statusCol} ?? null) : null,
            ];
        })->toArray();
    }

    private function queryJobsNeedingAccessInfo(string $jobsTable, int $teamId, Carbon $now): array
    {
        $cols = $this->cols($jobsTable);
        $startCol = isset($cols['scheduled_start']) ? 'scheduled_start' : (isset($cols['start_at']) ? 'start_at' : (isset($cols['scheduled_at']) ? 'scheduled_at' : null));
        $accessCol = isset($cols['access_instructions']) ? 'access_instructions' : (isset($cols['access_notes']) ? 'access_notes' : null);
        $statusCol = isset($cols['status']) ? 'status' : null;

        $q = DB::table($jobsTable)->where('team_id', $teamId);

        if ($startCol) {
            $q->whereBetween($startCol, [$now, $now->copy()->addHours(24)]);
        }
        if ($accessCol) {
            $q->where(function ($qq) use ($accessCol) {
                $qq->whereNull($accessCol)->orWhere($accessCol, '=', '');
            });
        }
        if ($statusCol) {
            $q->whereNotIn($statusCol, ['completed', 'cancelled']);
        }

        return $q->limit(200)->get()->map(function ($r) use ($startCol, $accessCol, $statusCol) {
            return [
                'id' => (int)($r->id ?? 0),
                'scheduled_start' => $startCol ? ($r->{$startCol} ?? null) : null,
                'access_instructions' => $accessCol ? ($r->{$accessCol} ?? null) : null,
                'status' => $statusCol ? ($r->{$statusCol} ?? null) : null,
            ];
        })->toArray();
    }

    private function queryJobsLateToStart(string $jobsTable, int $teamId, Carbon $now): array
    {
        $cols = $this->cols($jobsTable);
        $startCol = isset($cols['scheduled_start']) ? 'scheduled_start' : (isset($cols['start_at']) ? 'start_at' : (isset($cols['scheduled_at']) ? 'scheduled_at' : null));
        $statusCol = isset($cols['status']) ? 'status' : null;

        if (!$startCol || !$statusCol) return [];

        $q = DB::table($jobsTable)
            ->where('team_id', $teamId)
            ->where($startCol, '<', $now->copy()->subMinutes(30))
            ->whereIn($statusCol, ['scheduled', 'confirmed']);

        return $q->limit(200)->get()->map(function ($r) use ($startCol, $statusCol) {
            return [
                'id' => (int)($r->id ?? 0),
                'scheduled_start' => $r->{$startCol} ?? null,
                'status' => $r->{$statusCol} ?? null,
            ];
        })->toArray();
    }

    private function queryRecurringDrift(string $jobsTable, int $teamId, Carbon $now): array
    {
        $cols = $this->cols($jobsTable);
        $isRecurringCol = isset($cols['is_recurring']) ? 'is_recurring' : null;
        $nextOccCol = isset($cols['next_occurrence']) ? 'next_occurrence' : (isset($cols['next_occurrence_at']) ? 'next_occurrence_at' : null);
        $intervalCol = isset($cols['expected_interval_days']) ? 'expected_interval_days' : (isset($cols['recurring_interval_days']) ? 'recurring_interval_days' : null);

        $q = DB::table($jobsTable)->where('team_id', $teamId);
        if ($isRecurringCol) {
            $q->where($isRecurringCol, 1);
        }

        $rows = $q->limit(500)->get();
        $out = [];
        foreach ($rows as $r) {
            $nextOcc = $nextOccCol ? ($r->{$nextOccCol} ?? null) : null;
            $interval = $intervalCol ? (int)($r->{$intervalCol} ?? 0) : 0;

            if (!$nextOcc) {
                $out[] = ['id' => (int)($r->id ?? 0), 'next_occurrence' => null, 'expected_interval_days' => $interval];
                continue;
            }

            if ($interval > 0) {
                try {
                    $dt = Carbon::parse($nextOcc, $now->getTimezone());
                    // gap > interval in the future
                    $gap = $now->diffInDays($dt, false);
                    if ($gap > $interval) {
                        $out[] = ['id' => (int)($r->id ?? 0), 'next_occurrence' => $nextOcc, 'expected_interval_days' => $interval];
                    }
                } catch (\Throwable $e) {
                    // ignore parse errors
                }
            }
        }
        return $out;
    }

    private function queryOverdueJobs(string $jobsTable, int $teamId, Carbon $now): array
    {
        $cols = $this->cols($jobsTable);
        $endCol = isset($cols['scheduled_end']) ? 'scheduled_end' : (isset($cols['end_at']) ? 'end_at' : null);
        $statusCol = isset($cols['status']) ? 'status' : null;
        if (!$endCol || !$statusCol) return [];

        $q = DB::table($jobsTable)
            ->where('team_id', $teamId)
            ->where($endCol, '<', $now)
            ->whereNotIn($statusCol, ['completed', 'cancelled']);

        return $q->orderBy($endCol, 'asc')->limit(200)->get()->map(function ($r) use ($endCol, $statusCol) {
            return [
                'id' => (int)($r->id ?? 0),
                'scheduled_end' => $r->{$endCol} ?? null,
                'status' => $r->{$statusCol} ?? null,
            ];
        })->toArray();
    }

    /**
     * Rule 15 — Absent Client Pattern (weekly)
     * recurring_job AND last_completed > expected_interval + 7 days
     */
    private function queryAbsentClientPattern(string $jobsTable, int $teamId, Carbon $now): array
    {
        $cols = $this->cols($jobsTable);
        $isRecurringCol = isset($cols['is_recurring']) ? 'is_recurring' : (isset($cols['recurring']) ? 'recurring' : null);
        $lastCompletedCol = isset($cols['last_completed_at']) ? 'last_completed_at' : (isset($cols['completed_at']) ? 'completed_at' : null);
        $intervalCol = isset($cols['expected_interval_days']) ? 'expected_interval_days' : (isset($cols['recurring_interval_days']) ? 'recurring_interval_days' : null);

        if (!$isRecurringCol || !$lastCompletedCol || !$intervalCol) return [];

        $rows = DB::table($jobsTable)
            ->where('team_id', $teamId)
            ->where($isRecurringCol, 1)
            ->limit(1000)
            ->get();

        $out = [];
        foreach ($rows as $r) {
            $interval = (int)($r->{$intervalCol} ?? 0);
            if ($interval <= 0) continue;

            $last = $r->{$lastCompletedCol} ?? null;
            if (!$last) continue;

            try {
                $lastDt = Carbon::parse($last, $now->getTimezone());
                $expectedNext = $lastDt->copy()->addDays($interval);
                if ($now->greaterThan($expectedNext->addDays(7))) {
                    $out[] = [
                        'id' => (int)($r->id ?? 0),
                        'last_completed_at' => $last,
                        'expected_interval_days' => $interval,
                    ];
                }
            } catch (\Throwable $e) {
                // ignore parse errors
            }
        }

        return $out;
    }

private function logRun(int $teamId, int $companyId, ?int $userId, ?int $signalId, ?string $idempotencyKey, string $status, ?array $result, ?string $error): void
    {
        DB::table('tz_automation_runs')->insert([
            'team_id' => $teamId,
            'company_id' => $companyId,
            'user_id' => $userId,
            'agent_type' => 'titan_work_automation',
            'signal_id' => $signalId,
            'idempotency_key' => $idempotencyKey,
            'status' => $status,
            'result_json' => $result ? json_encode($result, JSON_UNESCAPED_UNICODE) : null,
            'error' => $error,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function cols(string $table): array
    {
        $cols = [];
        foreach (Schema::getColumnListing($table) as $c) {
            $cols[$c] = true;
        }
        return $cols;
    }
}
