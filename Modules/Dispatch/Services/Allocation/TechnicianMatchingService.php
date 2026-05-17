<?php

declare(strict_types=1);

namespace Modules\Dispatch\Services\Allocation;

use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Modules\Dispatch\Models\AssignShift;
use Modules\Dispatch\Models\TechnicianProfile;

class TechnicianMatchingService
{
    public function rank(array $jobContext = []): Collection
    {
        $startsAt = isset($jobContext['starts_at']) ? \Illuminate\Support\Carbon::parse($jobContext['starts_at']) : null;
        $requiredSkillIds = collect($jobContext['skill_ids'] ?? [])->filter()->map(fn ($id) => (int) $id)->values();
        $zoneId = $jobContext['service_zone_id'] ?? null;

        return TechnicianProfile::query()
            ->with(['user', 'skills', 'defaultZone'])
            ->where('active', true)
            ->when($jobContext['company_id'] ?? null, fn ($query, $companyId) => $query->where('company_id', $companyId))
            ->get()
            ->map(function (TechnicianProfile $technician) use ($startsAt, $requiredSkillIds, $zoneId) {
                $score = 50;
                $reasons = [];

                $skillMatches = $requiredSkillIds->intersect($technician->skills->pluck('id'))->count();
                if ($requiredSkillIds->isEmpty()) {
                    $score += 10;
                    $reasons[] = 'No mandatory skills specified.';
                } elseif ($skillMatches === $requiredSkillIds->count()) {
                    $score += 30;
                    $reasons[] = 'All required skills match.';
                } elseif ($skillMatches > 0) {
                    $score += 10;
                    $reasons[] = 'Some required skills match.';
                } else {
                    $score -= 25;
                    $reasons[] = 'Required skills missing.';
                }

                if ($zoneId && (int) $technician->default_zone_id === (int) $zoneId) {
                    $score += 20;
                    $reasons[] = 'Default service zone matches.';
                } elseif ($zoneId) {
                    $score -= 5;
                    $reasons[] = 'Different default service zone.';
                }

                if ($startsAt instanceof CarbonInterface) {
                    $hasAssignment = AssignShift::query()
                        ->where('employee_id', $technician->user_id)
                        ->whereDate('date_added', $startsAt->toDateString())
                        ->exists();

                    if ($hasAssignment) {
                        $score += 15;
                        $reasons[] = 'Technician has shift coverage on the job date.';
                    } else {
                        $score -= 10;
                        $reasons[] = 'No shift coverage found for the job date.';
                    }
                }

                return [
                    'technician' => $technician,
                    'score' => max(0, min(100, $score)),
                    'reasons' => $reasons,
                ];
            })
            ->sortByDesc('score')
            ->values();
    }
}
