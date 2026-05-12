<?php

namespace Modules\GroundZeroOps\Services;

use Illuminate\Support\Facades\DB;
use Modules\GroundZeroOps\Models\Dispatch;
use Modules\GroundZeroOps\Models\GroundZeroJob;
use Modules\GroundZeroOps\Support\DTOs\DispatchSuggestion;

class DispatchService
{
    /**
     * @param  array{company_id:int,job_id:int,technician_id:int,assigned_by:int|null,status?:string}  $payload
     */
    public function assign(array $payload): Dispatch
    {
        return DB::transaction(function () use ($payload): Dispatch {
            $job = GroundZeroJob::query()
                ->where('company_id', $payload['company_id'])
                ->findOrFail($payload['job_id']);

            $job->forceFill([
                'assigned_technician_id' => $payload['technician_id'],
                'status' => 'assigned',
            ])->save();

            return Dispatch::query()->create([
                'company_id' => $payload['company_id'],
                'job_id' => $job->id,
                'technician_id' => $payload['technician_id'],
                'status' => $payload['status'] ?? 'assigned',
                'assigned_by' => $payload['assigned_by'] ?? null,
                'assigned_at' => now(),
            ]);
        });
    }

    /**
     * @return array<int, array<string, int>>
     */
    public function rankedTechniciansForJob(int $companyId, int $jobId): array
    {
        $technicians = DB::table('ground_zero_shifts')
            ->leftJoin('ground_zero_dispatches', function ($join) use ($companyId): void {
                $join->on('ground_zero_dispatches.technician_id', '=', 'ground_zero_shifts.technician_id')
                    ->where('ground_zero_dispatches.company_id', '=', $companyId)
                    ->where('ground_zero_dispatches.status', '!=', 'completed');
            })
            ->where('ground_zero_shifts.company_id', $companyId)
            ->where('ground_zero_shifts.status', 'active')
            ->groupBy('ground_zero_shifts.technician_id')
            ->selectRaw('ground_zero_shifts.technician_id, COUNT(ground_zero_dispatches.id) as open_dispatch_count')
            ->orderBy('open_dispatch_count')
            ->orderBy('ground_zero_shifts.technician_id')
            ->limit(5)
            ->get();

        $rank = 1;

        return $technicians
            ->map(function ($item) use (&$rank): array {
                $suggestion = new DispatchSuggestion((int) $item->technician_id, (int) $item->open_dispatch_count, $rank++);

                return $suggestion->toArray();
            })
            ->values()
            ->all();
    }
}
