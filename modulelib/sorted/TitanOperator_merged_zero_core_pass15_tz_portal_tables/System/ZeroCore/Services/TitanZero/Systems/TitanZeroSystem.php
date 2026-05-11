<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Systems;

use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Coordination\AICoreCoordinator;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Coordination\EquilibriumResolver;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Governance\BosApprovalValidator;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Governance\GovernanceRules;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Context\SignalEnvelopeBuilder;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Registry\TitanToolRegistry;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Plugins\Registry\PluginRegistry;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support\SignalRegistry;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanZero\Support\ZeroSignal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TitanZeroSystem
{
    public function __construct(
        protected SignalEnvelopeBuilder $envelopes,
        protected AICoreCoordinator $coordinator,
        protected EquilibriumResolver $resolver,
        protected GovernanceRules $governance,
        protected BosApprovalValidator $bosValidator,
        protected TitanToolRegistry $tools,
        protected PluginRegistry $plugins,
    ) {}

    public function dashboard(): array
    {
        $user = Auth::user();
        $teamId = $user?->team_id;
        $envelope = $this->envelopes->build($teamId, $user?->id);

        return [
            'envelope' => $envelope,
            'readiness' => config('titan_operator.zero.readiness', []),
            'tools' => $this->tools->all(),
            'proposals' => $this->recentProposals($teamId),
            'audit' => $this->recentAudit($teamId),
            'plugins' => $this->plugins->manifest(),
        ];
    }

    public function propose(string $intent, array $payload = []): array
    {
        $user = Auth::user();
        $teamId = $user?->team_id;
        $envelope = $this->envelopes->build($teamId, $user?->id);
        $coordination = $this->coordinator->coordinate($intent, $envelope, $payload);
        $resolved = $this->resolver->resolve($coordination, $envelope, $payload);
        $governed = $this->governance->evaluate($resolved + ['risk' => $coordination['risk'] ?? 'low'], $payload);
        $bosValidation = $this->bosValidator->validate($governed);

        $registryKey = 'zero.proposal.created';
        $registry = SignalRegistry::get($registryKey);

        $decision = [
            'coordination' => $coordination,
            'resolved' => $resolved,
            'governed' => $governed,
            'bos_validation' => $bosValidation,
            'risk' => $coordination['risk'] ?? 'low',
            'decision' => $bosValidation['status'] ?? 'processing',
            'registry' => $registry,
            'registry_key' => $registryKey,
        ];

        $proposal = [
            'team_id' => $teamId,
            'company_id' => $teamId,
            'user_id' => $user?->id,
            'intent' => $intent,
            'risk' => $decision['risk'] ?? 'low',
            'status' => ZeroSignal::initialProposalStatus(),
            'payload' => json_encode($payload),
            'decision_payload' => json_encode($decision),
            'review_notes' => null,
            'reviewed_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasTable(config('titan_operator.zero.proposal_table'))) {
            $insert = $proposal;
            $proposalTable = config('titan_operator.zero.proposal_table');

            if (Schema::hasColumn($proposalTable, 'signal_stage')) {
                $insert['signal_stage'] = data_get($decision, 'registry.stage', data_get($decision, 'resolved.signal.state', 'signal'));
            }

            if (Schema::hasColumn($proposalTable, 'process_status')) {
                $insert['process_status'] = data_get($decision, 'bos_validation.status', 'processing');
            }

            $id = DB::table($proposalTable)->insertGetId($insert);
            $proposal['id'] = $id;
            $this->logAudit($teamId, $user?->id, 'zero.proposal.created', ['proposal_id' => $id, 'intent' => $intent, 'node' => data_get($decision, 'resolved.signal.node_origin', 'server'), 'registry' => $registry]);
            $this->logAudit($teamId, $user?->id, 'zero.equilibrium.decided', ['proposal_id' => $id, 'equilibrium' => data_get($decision, 'resolved.equilibrium', []), 'decision' => data_get($decision, 'decision')]);

            if ($auditEvent = data_get($decision, 'governed.audit_event')) {
                $this->logAudit($teamId, $user?->id, $auditEvent, ['proposal_id' => $id, 'governed' => data_get($decision, 'governed', [])]);
            }

            if (Schema::hasTable(config('titan_operator.zero.decision_table'))) {
                DB::table(config('titan_operator.zero.decision_table'))->insert([
                    'proposal_id' => $id,
                    'team_id' => $teamId,
                    'company_id' => $teamId,
                    'decision_key' => data_get($decision, 'decision', 'processing'),
                    'decision_reason' => json_encode([
                        'equilibrium' => data_get($decision, 'resolved.equilibrium', []),
                        'governed' => data_get($decision, 'governed', []),
                        'bos_validation' => data_get($decision, 'bos_validation', []),
                    ]),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } else {
            $proposal['id'] = null;
        }

        return ['proposal' => $proposal, 'decision' => $decision, 'envelope' => $envelope];
    }

    public function updateProposalStatus(int $proposalId, string $status, ?string $notes = null): bool
    {
        $status = ZeroSignal::normalizeReviewStatus($status);
        $table = config('titan_operator.zero.proposal_table');
        $actionsTable = config('titan_operator.zero.proposal_action_table');
        $user = Auth::user();
        $teamId = $user?->team_id;

        if (! Schema::hasTable($table)) {
            return false;
        }

        $query = DB::table($table)->where('id', $proposalId);

        if ($teamId && Schema::hasColumn($table, 'team_id')) {
            $query->where('team_id', $teamId);
        }

        $updates = [
            'status' => $status,
            'review_notes' => $notes,
            'reviewed_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn($table, 'signal_stage')) {
            $updates['signal_stage'] = ZeroSignal::stageForStatus($status);
        }

        if (Schema::hasColumn($table, 'process_status')) {
            $updates['process_status'] = $status === 'processed' ? 'processed' : 'processing';
        }

        $updated = $query->update($updates);

        if (! $updated) {
            return false;
        }

        if (Schema::hasTable($actionsTable)) {
            DB::table($actionsTable)->insert([
                'proposal_id' => $proposalId,
                'team_id' => $teamId,
                'company_id' => $teamId,
                'user_id' => $user?->id,
                'action_key' => 'review.' . $status,
                'action_status' => $status,
                'meta_json' => json_encode(['notes' => $notes]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->logAudit($teamId, $user?->id, ZeroSignal::auditEventForStatus($status), [
            'registry_stage' => SignalRegistry::stageFor(ZeroSignal::auditEventForStatus($status), ZeroSignal::stageForStatus($status)),
            'proposal_id' => $proposalId,
            'notes' => $notes,
        ]);

        return true;
    }

    public function recentProposals(?int $teamId): array
    {
        $table = config('titan_operator.zero.proposal_table');
        if (! Schema::hasTable($table)) {
            return [];
        }

        $query = DB::table($table)->orderByDesc('id')->limit(10);
        if ($teamId && Schema::hasColumn($table, 'team_id')) {
            $query->where('team_id', $teamId);
        }

        return $query->get()->map(fn ($row) => (array) $row)->all();
    }

    public function recentAudit(?int $teamId): array
    {
        $table = config('titan_operator.zero.audit_table');
        if (! Schema::hasTable($table)) {
            return [];
        }

        $query = DB::table($table)->orderByDesc('id')->limit(15);
        if ($teamId && Schema::hasColumn($table, 'team_id')) {
            $query->where('team_id', $teamId);
        }

        return $query->get()->map(fn ($row) => (array) $row)->all();
    }

    public function logAudit(?int $teamId, ?int $userId, string $event, array $meta = []): void
    {
        $table = config('titan_operator.zero.audit_table');
        if (! Schema::hasTable($table)) {
            return;
        }

        DB::table($table)->insert([
            'team_id' => $teamId,
            'company_id' => $teamId,
            'user_id' => $userId,
            'event_key' => $event,
            'meta_json' => json_encode($meta),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function bossDashboard(): array
    {
        $user = Auth::user();
        $teamId = $user?->team_id;

        return [
            'surface' => 'boss',
            'title' => 'Titan Boss',
            'subtitle' => 'Owner command centre for oversight, approvals, workload, and quality pressure.',
            'metrics' => [
                ['label' => 'Active Jobs', 'value' => $this->countTeamRows('tz_jobs', $teamId, fn ($q) => $q->whereNotIn('status', ['completed', 'cancelled']))],
                ['label' => 'Due Today', 'value' => $this->countTeamRows('tz_jobs', $teamId, fn ($q) => $q->whereDate('service_date', now()->toDateString()))],
                ['label' => 'Unassigned', 'value' => $this->bossUnassignedCount($teamId)],
                ['label' => 'Open Evidence', 'value' => $this->countTeamRows('tz_evidence_sets', $teamId, fn ($q) => $q->whereIn('status', ['open', 'submitted']))],
                ['label' => 'Inspection Failures', 'value' => $this->bossInspectionFailureCount($teamId)],
                ['label' => 'Voice Sessions Today', 'value' => $this->countTeamRows('tz_voice_sessions', $teamId, fn ($q) => $q->whereDate('started_at', now()->toDateString()))],
            ],
            'jobs' => $this->bossJobs($teamId),
            'audit' => $this->recentAudit($teamId),
            'plugins' => $this->plugins->manifest(),
        ];
    }

    public function goDashboard(): array
    {
        $user = Auth::user();
        $teamId = $user?->team_id;
        $userId = $user?->id;

        return [
            'surface' => 'go',
            'title' => 'Titan Go',
            'subtitle' => 'Field execution surface for today\'s work, checklist completion, evidence, and voice-guided flow.',
            'metrics' => [
                ['label' => 'My Jobs Today', 'value' => $this->goJobsTodayCount($teamId, $userId)],
                ['label' => 'In Progress', 'value' => $this->goInProgressCount($teamId, $userId)],
                ['label' => 'Checklist Responses', 'value' => $this->countTeamRows('tz_job_checklist_responses', $teamId, fn ($q) => $q->where('user_id', $userId))],
                ['label' => 'Open Evidence', 'value' => $this->countTeamRows('tz_evidence_sets', $teamId, fn ($q) => $q->where('user_id', $userId)->whereIn('status', ['open', 'submitted']))],
                ['label' => 'Planned Inspections', 'value' => $this->countTeamRows('tz_inspections', $teamId, fn ($q) => $q->where('inspector_user_id', $userId)->whereIn('status', ['planned', 'scheduled']))],
                ['label' => 'Voice Sessions Today', 'value' => $this->countTeamRows('tz_voice_sessions', $teamId, fn ($q) => $q->where('user_id', $userId)->whereDate('started_at', now()->toDateString()))],
            ],
            'jobs' => $this->goJobs($teamId, $userId),
            'voice_transcripts' => $this->goVoiceTranscripts($teamId, $userId),
            'audit' => $this->recentAudit($teamId),
        ];
    }

    protected function countTeamRows(string $table, ?int $teamId, ?callable $callback = null): int
    {
        if (! Schema::hasTable($table)) {
            return 0;
        }

        $query = DB::table($table);

        if ($teamId && Schema::hasColumn($table, 'team_id')) {
            $query->where('team_id', $teamId);
        }

        if ($callback) {
            $callback($query);
        }

        return (int) $query->count();
    }

    protected function bossUnassignedCount(?int $teamId): int
    {
        if (! Schema::hasTable('tz_jobs')) {
            return 0;
        }

        $query = DB::table('tz_jobs');
        if ($teamId && Schema::hasColumn('tz_jobs', 'team_id')) {
            $query->where('tz_jobs.team_id', $teamId);
        }

        if (Schema::hasTable('tz_job_assignments')) {
            $query->leftJoin('tz_job_assignments', 'tz_job_assignments.job_id', '=', 'tz_jobs.id')
                ->whereNull('tz_job_assignments.id');
        } elseif (Schema::hasColumn('tz_jobs', 'user_id')) {
            $query->whereNull('tz_jobs.user_id');
        }

        return (int) $query->count('tz_jobs.id');
    }

    protected function bossInspectionFailureCount(?int $teamId): int
    {
        if (! Schema::hasTable('tz_inspection_items')) {
            return 0;
        }

        $query = DB::table('tz_inspection_items')->where('result', 'fail');
        if (Schema::hasColumn('tz_inspection_items', 'team_id') && $teamId) {
            $query->where('team_id', $teamId);
        }

        return (int) $query->count();
    }

    protected function bossJobs(?int $teamId): array
    {
        if (! Schema::hasTable('tz_jobs')) {
            return [];
        }

        $query = DB::table('tz_jobs')
            ->select(['tz_jobs.id', 'tz_jobs.title', 'tz_jobs.status', 'tz_jobs.priority', 'tz_jobs.service_date', 'tz_jobs.scheduled_start_at', 'tz_customers.name as customer_name'])
            ->leftJoin('tz_customers', 'tz_customers.id', '=', 'tz_jobs.customer_id')
            ->orderByRaw('CASE WHEN tz_jobs.service_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('tz_jobs.service_date')
            ->limit(8);

        if ($teamId && Schema::hasColumn('tz_jobs', 'team_id')) {
            $query->where('tz_jobs.team_id', $teamId);
        }

        return $query->get()->map(fn ($row) => (array) $row)->all();
    }

    protected function goJobsTodayCount(?int $teamId, ?int $userId): int
    {
        if (! Schema::hasTable('tz_jobs')) {
            return 0;
        }

        return count($this->goJobs($teamId, $userId, true));
    }

    protected function goInProgressCount(?int $teamId, ?int $userId): int
    {
        if (! Schema::hasTable('tz_jobs')) {
            return 0;
        }

        $jobs = collect($this->goJobs($teamId, $userId))->whereIn('status', ['assigned', 'scheduled', 'in_progress', 'started']);

        return $jobs->count();
    }

    protected function goJobs(?int $teamId, ?int $userId, bool $todayOnly = false): array
    {
        if (! Schema::hasTable('tz_jobs')) {
            return [];
        }

        $query = DB::table('tz_jobs')
            ->select(['tz_jobs.id', 'tz_jobs.title', 'tz_jobs.status', 'tz_jobs.priority', 'tz_jobs.service_date', 'tz_jobs.scheduled_start_at', 'tz_customers.name as customer_name'])
            ->leftJoin('tz_customers', 'tz_customers.id', '=', 'tz_jobs.customer_id')
            ->orderByRaw('CASE WHEN tz_jobs.service_date IS NULL THEN 1 ELSE 0 END')
            ->orderBy('tz_jobs.service_date')
            ->limit(8);

        if ($teamId && Schema::hasColumn('tz_jobs', 'team_id')) {
            $query->where('tz_jobs.team_id', $teamId);
        }

        if ($todayOnly) {
            $query->whereDate('tz_jobs.service_date', now()->toDateString());
        }

        if (Schema::hasTable('tz_job_assignments')) {
            $query->join('tz_job_assignments', function ($join) use ($userId): void {
                $join->on('tz_job_assignments.job_id', '=', 'tz_jobs.id');
                if ($userId) {
                    $join->where('tz_job_assignments.user_id', '=', $userId);
                }
            });
        } elseif ($userId && Schema::hasColumn('tz_jobs', 'user_id')) {
            $query->where('tz_jobs.user_id', $userId);
        }

        return $query->get()->unique('id')->map(fn ($row) => (array) $row)->values()->all();
    }

    protected function goVoiceTranscripts(?int $teamId, ?int $userId): array
    {
        if (! Schema::hasTable('tz_voice_transcripts')) {
            return [];
        }

        $query = DB::table('tz_voice_transcripts')
            ->select(['id', 'entity_type', 'entity_id', 'language', 'provider', 'status', 'transcribed_at'])
            ->orderByDesc('id')
            ->limit(6);

        if ($teamId && Schema::hasColumn('tz_voice_transcripts', 'team_id')) {
            $query->where('team_id', $teamId);
        }

        if ($userId && Schema::hasColumn('tz_voice_transcripts', 'user_id')) {
            $query->where('user_id', $userId);
        }

        return $query->get()->map(fn ($row) => (array) $row)->all();
    }

}
