<?php

namespace Modules\TitanEchoAssist\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\TitanEchoAssist\Support\WorkcoreSchemaMap;

class WorkcoreSchedulingDataService
{
    public function __construct(private readonly WorkcorePortalDataService $workcorePortalDataService) {}

    public function getNextVisit(int $customerId, int $companyId): ?array
    {
        return $this->workcorePortalDataService->getUpcomingVisits($customerId, $companyId, 1)[0] ?? null;
    }

    public function getVisitCountThisMonth(int $customerId, int $companyId): int
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::JOBS_TABLE)) {
            return 0;
        }

        return DB::table(WorkcoreSchemaMap::JOBS_TABLE)
            ->where($this->companyField(WorkcoreSchemaMap::JOBS_TABLE), $companyId)
            ->where(WorkcoreSchemaMap::CUSTOMER_ID_FIELD, $customerId)
            ->whereNotNull(WorkcoreSchemaMap::SCHEDULED_AT_FIELD)
            ->whereBetween(WorkcoreSchemaMap::SCHEDULED_AT_FIELD, [now()->startOfMonth(), now()->endOfMonth()])
            ->count();
    }

    public function getRecurringServiceStatus(int $customerId, int $companyId): array
    {
        if (! Schema::hasTable(WorkcoreSchemaMap::RECURRING_SERVICES_TABLE)) {
            return [
                'active' => false,
                'is_paused' => false,
                'skip_next' => false,
                'frequency' => null,
                'pause_until' => null,
            ];
        }

        $service = DB::table(WorkcoreSchemaMap::RECURRING_SERVICES_TABLE)
            ->where(WorkcoreSchemaMap::LEGACY_COMPANY_ID_FIELD, $companyId)
            ->where(WorkcoreSchemaMap::CUSTOMER_ID_FIELD, $customerId)
            ->orderByDesc('id')
            ->first(['frequency', 'is_paused', 'skip_next', 'pause_until', 'notes']);

        if ($service === null) {
            return [
                'active' => false,
                'is_paused' => false,
                'skip_next' => false,
                'frequency' => null,
                'pause_until' => null,
            ];
        }

        return [
            'active' => true,
            'is_paused' => (bool) ($service->is_paused ?? false),
            'skip_next' => (bool) ($service->skip_next ?? false),
            'frequency' => $service->frequency,
            'pause_until' => $service->pause_until,
            'notes' => $service->notes ?? null,
        ];
    }

    private function companyField(string $table): string
    {
        if (Schema::hasColumn($table, WorkcoreSchemaMap::COMPANY_ID_FIELD)) {
            return WorkcoreSchemaMap::COMPANY_ID_FIELD;
        }

        return WorkcoreSchemaMap::LEGACY_COMPANY_ID_FIELD;
    }
}
