<?php

namespace App\Filament\TitanGo\Widgets;

use App\Models\DriverLocation;
use App\Models\Job;
use App\Models\User;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class TitanGoDashboardWidget extends Widget
{
    protected string $view = 'filament.titango.widgets.titan-go-dashboard-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getViewData(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return [
                'stats' => [
                    'active_technicians' => 0,
                    'active_jobs' => 0,
                    'scheduled_jobs' => 0,
                    'offline_technicians' => 0,
                ],
            ];
        }

        $activeTechnicians = User::query()
            ->where('organization_id', $organizationId)
            ->whereHas('roles', fn ($query) => $query->where('name', 'technician'))
            ->count();

        $activeJobs = Job::query()
            ->where('organization_id', $organizationId)
            ->whereIn('status', [Job::STATUS_EN_ROUTE, Job::STATUS_IN_PROGRESS])
            ->count();

        $scheduledJobs = Job::query()
            ->where('organization_id', $organizationId)
            ->whereIn('status', [Job::STATUS_SCHEDULED, Job::STATUS_ASSIGNED])
            ->count();

        $latestLocations = DriverLocation::query()
            ->where('organization_id', $organizationId)
            ->selectRaw('MAX(recorded_at) as recorded_at')
            ->groupBy('user_id')
            ->get();

        $offlineTechnicians = $latestLocations
            ->filter(fn ($location) => $location->recorded_at !== null && Carbon::parse($location->recorded_at)->lt(now()->subMinutes(30)))
            ->count();

        return [
            'stats' => [
                'active_technicians' => $activeTechnicians,
                'active_jobs' => $activeJobs,
                'scheduled_jobs' => $scheduledJobs,
                'offline_technicians' => $offlineTechnicians,
            ],
        ];
    }
}
