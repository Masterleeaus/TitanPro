<?php

namespace Modules\TitanEchoAssist\Services;

use App\Models\Invoice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TitanEchoAssist\Support\WorkcoreSchemaMap;

class WorkcorePortalDataService
{
    public function getUpcomingVisits(int $customerId, int $companyId, int $limit = 5): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::JOBS_TABLE)) {
            return [];
        }

        return DB::table(WorkcoreSchemaMap::JOBS_TABLE)
            ->where($this->companyField(WorkcoreSchemaMap::JOBS_TABLE), $companyId)
            ->where(WorkcoreSchemaMap::CUSTOMER_ID_FIELD, $customerId)
            ->whereIn(WorkcoreSchemaMap::STATUS_FIELD, ['scheduled', 'assigned'])
            ->whereNotNull(WorkcoreSchemaMap::SCHEDULED_AT_FIELD)
            ->whereDate(WorkcoreSchemaMap::SCHEDULED_AT_FIELD, '>=', now()->toDateString())
            ->orderBy(WorkcoreSchemaMap::SCHEDULED_AT_FIELD)
            ->limit($limit)
            ->get([
                'id',
                'title',
                'status',
                'scheduled_at',
                'completed_at',
                'property_id',
            ])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    public function getPastVisits(int $customerId, int $companyId, int $limit = 10): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::JOBS_TABLE)) {
            return [];
        }

        return DB::table(WorkcoreSchemaMap::JOBS_TABLE)
            ->where($this->companyField(WorkcoreSchemaMap::JOBS_TABLE), $companyId)
            ->where(WorkcoreSchemaMap::CUSTOMER_ID_FIELD, $customerId)
            ->whereNotNull(WorkcoreSchemaMap::COMPLETED_AT_FIELD)
            ->orderByDesc(WorkcoreSchemaMap::COMPLETED_AT_FIELD)
            ->limit($limit)
            ->get([
                'id',
                'title',
                'status',
                'scheduled_at',
                'completed_at',
                'property_id',
            ])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    public function getInvoices(int $customerId, int $companyId, string $status = null): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::INVOICES_TABLE)) {
            return [];
        }

        $query = DB::table(WorkcoreSchemaMap::INVOICES_TABLE)
            ->where($this->companyField(WorkcoreSchemaMap::INVOICES_TABLE), $companyId)
            ->where(WorkcoreSchemaMap::CUSTOMER_ID_FIELD, $customerId);

        if ($status !== null) {
            $query->where(WorkcoreSchemaMap::STATUS_FIELD, $status);
        }

        return $query->orderByDesc(WorkcoreSchemaMap::DUE_AT_FIELD)
            ->get([
                'id',
                'invoice_number',
                'status',
                'balance_due',
                'due_at',
                'issued_at',
                'total',
                'amount_paid',
                'job_id',
            ])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    public function getOutstandingBalance(int $customerId, int $companyId): float
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::INVOICES_TABLE)) {
            return 0.0;
        }

        return (float) DB::table(WorkcoreSchemaMap::INVOICES_TABLE)
            ->where($this->companyField(WorkcoreSchemaMap::INVOICES_TABLE), $companyId)
            ->where(WorkcoreSchemaMap::CUSTOMER_ID_FIELD, $customerId)
            ->whereNotIn(WorkcoreSchemaMap::STATUS_FIELD, [Invoice::STATUS_PAID, Invoice::STATUS_VOID])
            ->where(WorkcoreSchemaMap::BALANCE_DUE_FIELD, '>', 0)
            ->sum(WorkcoreSchemaMap::BALANCE_DUE_FIELD);
    }

    public function getQuotes(int $customerId, int $companyId, string $status = null): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::QUOTES_TABLE)) {
            return [];
        }

        $query = DB::table(WorkcoreSchemaMap::QUOTES_TABLE)
            ->where($this->companyField(WorkcoreSchemaMap::QUOTES_TABLE), $companyId)
            ->where(WorkcoreSchemaMap::CUSTOMER_ID_FIELD, $customerId);

        if ($status !== null) {
            $query->where(WorkcoreSchemaMap::STATUS_FIELD, $status);
        }

        return $query->orderByDesc('created_at')
            ->get([
                'id',
                'estimate_number',
                'title',
                'status',
                'sent_at',
                'expires_at',
                'accepted_at',
                'declined_at',
                'job_id',
            ])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    public function getServiceIssues(int $customerId, int $companyId): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::JOB_MESSAGES_TABLE)) {
            return [];
        }

        $jobCompanyField = $this->companyField(WorkcoreSchemaMap::JOBS_TABLE);

        return DB::table(WorkcoreSchemaMap::JOB_MESSAGES_TABLE . ' as jm')
            ->join(WorkcoreSchemaMap::JOBS_TABLE . ' as j', 'j.id', '=', 'jm.job_id')
            ->where('j.' . $jobCompanyField, $companyId)
            ->where('jm.customer_id', $customerId)
            ->where('jm.status', 'failed')
            ->orderByDesc('jm.created_at')
            ->get([
                'jm.id',
                'jm.job_id',
                'jm.event',
                'jm.body',
                'jm.status',
                'jm.error',
                'jm.created_at',
            ])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    public function getJobChecklist(int $jobId, int $companyId): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::CHECKLIST_TABLE) || ! Schema::hasTable(WorkcoreSchemaMap::JOBS_TABLE)) {
            return [];
        }

        $jobCompanyField = $this->companyField(WorkcoreSchemaMap::JOBS_TABLE);

        return DB::table(WorkcoreSchemaMap::CHECKLIST_TABLE . ' as ci')
            ->join(WorkcoreSchemaMap::JOBS_TABLE . ' as j', 'j.id', '=', 'ci.job_id')
            ->where('ci.job_id', $jobId)
            ->where('j.' . $jobCompanyField, $companyId)
            ->orderBy('ci.sort_order')
            ->get([
                'ci.id',
                'ci.job_id',
                'ci.label',
                'ci.sort_order',
                'ci.is_required',
                'ci.completed_at',
            ])
            ->map(fn ($row): array => (array) $row)
            ->all();
    }

    public function getJobTimeline(int $jobId, int $companyId): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::JOBS_TABLE)) {
            return [];
        }

        $jobCompanyField = $this->companyField(WorkcoreSchemaMap::JOBS_TABLE);
        $job = DB::table(WorkcoreSchemaMap::JOBS_TABLE)
            ->where('id', $jobId)
            ->where($jobCompanyField, $companyId)
            ->first([
                'id',
                'status',
                'scheduled_at',
                'started_at',
                'completed_at',
                'cancelled_at',
                'created_at',
            ]);

        if ($job === null) {
            return [];
        }

        $timeline = [];

        foreach ([
            'created_at' => 'created',
            'scheduled_at' => 'scheduled',
            'started_at' => 'started',
            'completed_at' => 'completed',
            'cancelled_at' => 'cancelled',
        ] as $field => $event) {
            if (! empty($job->{$field})) {
                $timeline[] = [
                    'event' => $event,
                    'at' => $job->{$field},
                ];
            }
        }

        if (Schema::hasTable(WorkcoreSchemaMap::JOB_MESSAGES_TABLE)) {
            $messages = DB::table(WorkcoreSchemaMap::JOB_MESSAGES_TABLE)
                ->where('job_id', $jobId)
                ->orderBy('created_at')
                ->get(['event', 'status', 'created_at'])
                ->map(fn ($row): array => [
                    'event' => $row->event,
                    'status' => $row->status,
                    'at' => $row->created_at,
                ])
                ->all();

            $timeline = array_merge($timeline, $messages);
        }

        usort($timeline, fn (array $a, array $b): int => strcmp((string) $a['at'], (string) $b['at']));

        return $timeline;
    }

    public function getCustomerProfile(int $customerId, int $companyId): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::CUSTOMERS_TABLE)) {
            return [];
        }

        $customer = DB::table(WorkcoreSchemaMap::CUSTOMERS_TABLE)
            ->where('id', $customerId)
            ->where($this->companyField(WorkcoreSchemaMap::CUSTOMERS_TABLE), $companyId)
            ->first([
                'id',
                'first_name',
                'last_name',
                'email',
                'phone',
                'mobile',
                'notes',
                'created_at',
            ]);

        if ($customer === null) {
            return [];
        }

        $profile = (array) $customer;
        $profile['full_name'] = trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''));

        if (Schema::hasTable(WorkcoreSchemaMap::PROPERTIES_TABLE)) {
            $profile['properties'] = DB::table(WorkcoreSchemaMap::PROPERTIES_TABLE)
                ->where($this->companyField(WorkcoreSchemaMap::PROPERTIES_TABLE), $companyId)
                ->where(WorkcoreSchemaMap::CUSTOMER_ID_FIELD, $customerId)
                ->orderBy('id')
                ->get([
                    'id',
                    'name',
                    'address_line1',
                    'address_line2',
                    'city',
                    'state',
                    'postal_code',
                ])
                ->map(fn ($row): array => (array) $row)
                ->all();
        } else {
            $profile['properties'] = [];
        }

        return $profile;
    }

    public function getSiteProfile(int $propertyId, int $companyId): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::PROPERTIES_TABLE)) {
            return [];
        }

        $property = DB::table(WorkcoreSchemaMap::PROPERTIES_TABLE)
            ->where('id', $propertyId)
            ->where($this->companyField(WorkcoreSchemaMap::PROPERTIES_TABLE), $companyId)
            ->first([
                'id',
                'customer_id',
                'name',
                'address_line1',
                'address_line2',
                'city',
                'state',
                'postal_code',
                'country',
                'notes',
            ]);

        return $property ? (array) $property : [];
    }

    private function companyField(string $table): string
    {
        if (Schema::hasColumn($table, WorkcoreSchemaMap::COMPANY_ID_FIELD)) {
            return WorkcoreSchemaMap::COMPANY_ID_FIELD;
        }

        return WorkcoreSchemaMap::LEGACY_COMPANY_ID_FIELD;
    }
}
