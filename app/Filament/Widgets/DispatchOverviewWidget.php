<?php

namespace App\Filament\Widgets;

use App\Models\DriverLocation;
use App\Models\Job;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DispatchOverviewWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Dispatch';

    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $organizationId = auth()->user()?->organization_id;

        $activeCleaners = User::query()
            ->where('organization_id', $organizationId)
            ->whereHas('roles', fn ($query) => $query->whereIn('name', ['technician', 'cleaner', 'dispatcher']))
            ->count();

        $recentLocations = DriverLocation::query()
            ->whereHas('user', fn ($query) => $query->where('organization_id', $organizationId))
            ->where('recorded_at', '>=', now()->subMinutes(30))
            ->distinct('user_id')
            ->count('user_id');

        $enRoute = Job::query()
            ->where('organization_id', $organizationId)
            ->where('status', Job::STATUS_EN_ROUTE)
            ->count();

        $inProgress = Job::query()
            ->where('organization_id', $organizationId)
            ->where('status', Job::STATUS_IN_PROGRESS)
            ->count();

        return [
            Stat::make('Cleaners', $activeCleaners)->description('Field users in this organisation')->color('gray'),
            Stat::make('Live locations', $recentLocations)->description('Updated in the last 30 minutes')->color($recentLocations > 0 ? 'success' : 'warning'),
            Stat::make('En route', $enRoute)->description('Crews travelling to jobs')->color('warning'),
            Stat::make('In progress', $inProgress)->description('Jobs currently being cleaned')->color('info'),
        ];
    }
}
