<?php

namespace Modules\CleaningJobs\Services;

use Carbon\CarbonPeriod;
use Modules\CleaningJobs\Models\JobResourceAllocation;
use Modules\CleaningJobs\Models\JobResourceCapacity;

class JobResourcePlanner
{
    public function allocate(array $payload): JobResourceAllocation
    {
        $allocation = JobResourceAllocation::create($payload);
        $this->refreshCapacity((int) $allocation->user_id, $allocation->start_date, $allocation->end_date ?? $allocation->start_date);
        return $allocation;
    }

    public function refreshCapacity(int $userId, $startDate, $endDate): void
    {
        foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
            $allocatedHours = JobResourceAllocation::query()
                ->where('user_id', $userId)
                ->whereDate('start_date', '<=', $date)
                ->where(function ($q) use ($date) { $q->whereNull('end_date')->orWhereDate('end_date', '>=', $date); })
                ->whereIn('status', ['planned','active'])
                ->get()
                ->sum(fn ($row) => ((float) $row->hours_per_day) * ((float) $row->allocation_percentage / 100));

            JobResourceCapacity::updateOrCreate(
                ['user_id' => $userId, 'date' => $date->toDateString()],
                ['allocated_hours' => $allocatedHours]
            );
        }
    }
}
