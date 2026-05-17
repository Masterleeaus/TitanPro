<?php

namespace Modules\TitanEchoAssist\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class WorkcoreWorkerDataService
{
    public function getTechnicianTodaysJobs(int $userId, int $companyId): array
    {
        $query = DB::table('field_jobs')
            ->where($this->companyColumn('field_jobs'), $companyId)
            ->where($this->technicianColumn(), $userId)
            ->whereNull('deleted_at');

        $scheduledColumn = $this->scheduledColumn();
        if ($scheduledColumn !== null) {
            $query->whereDate($scheduledColumn, now()->toDateString());
        }

        return $query
            ->orderBy($scheduledColumn ?? 'id')
            ->get()
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    public function getCurrentJob(int $userId, int $companyId): ?array
    {
        $job = DB::table('field_jobs')
            ->where($this->companyColumn('field_jobs'), $companyId)
            ->where($this->technicianColumn(), $userId)
            ->whereIn('status', ['in_progress', 'arrived', 'en_route', 'assigned', 'scheduled'])
            ->whereNull('deleted_at')
            ->orderByRaw("CASE WHEN status = 'in_progress' THEN 0 ELSE 1 END")
            ->orderByDesc('started_at')
            ->orderBy($this->scheduledColumn() ?? 'id')
            ->first();

        return $job ? (array) $job : null;
    }

    public function getJobDetails(int $jobId, int $companyId): array
    {
        $job = DB::table('field_jobs')
            ->where('id', $jobId)
            ->where($this->companyColumn('field_jobs'), $companyId)
            ->whereNull('deleted_at')
            ->first();

        if (! $job) {
            return [];
        }

        return (array) $job;
    }

    public function getJobChecklist(int $jobId, int $companyId): array
    {
        $query = DB::table('job_checklist_items')
            ->join('field_jobs', 'field_jobs.id', '=', 'job_checklist_items.job_id')
            ->where('job_checklist_items.job_id', $jobId)
            ->where('field_jobs.'.$this->companyColumn('field_jobs'), $companyId)
            ->orderBy('job_checklist_items.sort_order');

        return $query
            ->get(['job_checklist_items.*'])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    public function createSiteDiaryEntry(int $jobId, int $companyId, string $content): array
    {
        $job = DB::table('field_jobs')
            ->where('id', $jobId)
            ->where($this->companyColumn('field_jobs'), $companyId)
            ->first();

        if (! $job) {
            return [];
        }

        $data = [
            'job_id' => $jobId,
            'customer_id' => (int) (($job->customer_id ?? 0) ?: 1),
            'channel' => 'internal',
            'event' => 'diary',
            'recipient' => 'technician:'.(auth()->id() ?? 'system'),
            'body' => $content,
            'status' => 'sent',
            'error' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        if (Schema::hasColumn('job_messages', 'organization_id')) {
            $data['organization_id'] = $companyId;
        }

        $id = DB::table('job_messages')->insertGetId($data);

        $created = DB::table('job_messages')->where('id', $id)->first();

        return $created ? (array) $created : [];
    }

    private function companyColumn(string $table): string
    {
        return Schema::hasColumn($table, 'company_id') ? 'company_id' : 'organization_id';
    }

    private function technicianColumn(): string
    {
        return Schema::hasColumn('field_jobs', 'technician_id') ? 'technician_id' : 'assigned_to';
    }

    private function scheduledColumn(): ?string
    {
        if (Schema::hasColumn('field_jobs', 'scheduled_start')) {
            return 'scheduled_start';
        }

        if (Schema::hasColumn('field_jobs', 'scheduled_at')) {
            return 'scheduled_at';
        }

        return null;
    }
}
