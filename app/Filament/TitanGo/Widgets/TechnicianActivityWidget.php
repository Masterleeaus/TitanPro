<?php

namespace App\Filament\TitanGo\Widgets;

use App\Models\DriverLocation;
use App\Models\Job;
use App\Models\User;
use Filament\Widgets\Widget;

class TechnicianActivityWidget extends Widget
{
    protected string $view = 'filament.titango.widgets.technician-activity-widget';

    protected int|string|array $columnSpan = [
        'default' => 'full',
        'xl' => 1,
    ];

    protected static ?int $sort = 2;

    protected function getViewData(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return ['technicians' => collect()];
        }

        $technicians = User::query()
            ->where('organization_id', $organizationId)
            ->whereHas('roles', fn ($query) => $query->where('name', 'technician'))
            ->withCount(['jobs as active_jobs_count' => fn ($query) => $query
                ->whereIn('status', [
                    Job::STATUS_SCHEDULED,
                    Job::STATUS_ASSIGNED,
                    Job::STATUS_EN_ROUTE,
                    Job::STATUS_IN_PROGRESS,
                ])])
            ->orderBy('name')
            ->get(['id', 'name']);

        $latestLocations = DriverLocation::query()
            ->where('organization_id', $organizationId)
            ->whereIn('user_id', $technicians->pluck('id'))
            ->orderByDesc('recorded_at')
            ->get(['user_id', 'recorded_at'])
            ->unique('user_id')
            ->keyBy('user_id');

        $staleThreshold = now()->subMinutes(30);

        return [
            'technicians' => $technicians->map(function (User $technician) use ($latestLocations, $staleThreshold) {
                $location = $latestLocations->get($technician->id);
                $lastSeen = $location?->recorded_at;
                $isOffline = $lastSeen === null || $lastSeen->lt($staleThreshold);

                return [
                    'id' => $technician->id,
                    'name' => $technician->name,
                    'active_jobs_count' => (int) $technician->active_jobs_count,
                    'last_seen' => $lastSeen?->diffForHumans(),
                    'status' => $isOffline ? 'Offline' : ($technician->active_jobs_count > 0 ? 'On Job' : 'Available'),
                ];
            }),
        ];
    }
}
