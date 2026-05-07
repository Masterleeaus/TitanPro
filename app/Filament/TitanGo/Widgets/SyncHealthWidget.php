<?php

namespace App\Filament\TitanGo\Widgets;

use App\Models\DriverLocation;
use App\Models\User;
use Filament\Widgets\Widget;

class SyncHealthWidget extends Widget
{
    protected string $view = 'filament.titango.widgets.sync-health-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 5;

    protected function getViewData(): array
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return ['rows' => collect()];
        }

        $technicians = User::query()
            ->where('organization_id', $organizationId)
            ->whereHas('roles', fn ($query) => $query->where('name', 'technician'))
            ->orderBy('name')
            ->get(['id', 'name']);

        $latestLocations = DriverLocation::query()
            ->where('organization_id', $organizationId)
            ->whereIn('user_id', $technicians->pluck('id'))
            ->selectRaw('user_id, MAX(recorded_at) as recorded_at')
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $threshold = now()->subMinutes(30);

        return [
            'rows' => $technicians->map(function (User $technician) use ($latestLocations, $threshold) {
                $lastSync = $latestLocations->get($technician->id)?->recorded_at;
                $isOffline = $lastSync === null || $lastSync->lt($threshold);

                return [
                    'name' => $technician->name,
                    'last_sync' => $lastSync,
                    'is_offline' => $isOffline,
                ];
            }),
        ];
    }
}
