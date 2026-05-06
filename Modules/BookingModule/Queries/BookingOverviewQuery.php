<?php

namespace Modules\BookingModule\Queries;

use Illuminate\Support\Facades\Schema;
use Modules\BookingModule\Entities\Schedule;
use Modules\BookingModule\Models\CleaningBooking;
use Modules\BookingModule\Support\TenantContext;

class BookingOverviewQuery
{
    public function counts(?int $companyId = null): array
    {
        $companyId = $companyId ?: TenantContext::companyId();

        if (!$companyId) {
            return ['bookings' => 0, 'schedules' => 0, 'unassigned_schedules' => 0];
        }

        return [
            'bookings' => $this->countModel(CleaningBooking::class, $companyId),
            'schedules' => $this->countModel(Schedule::class, $companyId),
            'unassigned_schedules' => $this->countSchedules($companyId, true),
        ];
    }

    protected function countModel(string $class, int $companyId): int
    {
        if (!class_exists($class)) {
            return 0;
        }

        $model = new $class();
        if (!Schema::hasTable($model->getTable()) || !Schema::hasColumn($model->getTable(), 'company_id')) {
            return 0;
        }

        return (int) $class::query()
            ->withoutGlobalScopes()
            ->where($model->getTable() . '.company_id', $companyId)
            ->count();
    }

    protected function countSchedules(int $companyId, bool $unassigned): int
    {
        $model = new Schedule();
        if (!Schema::hasTable($model->getTable()) || !Schema::hasColumn($model->getTable(), 'company_id')) {
            return 0;
        }

        $query = Schedule::query()
            ->withoutGlobalScopes()
            ->where($model->getTable() . '.company_id', $companyId);

        if ($unassigned) {
            $query->whereNull('assigned_to');
        }

        return (int) $query->count();
    }
}
