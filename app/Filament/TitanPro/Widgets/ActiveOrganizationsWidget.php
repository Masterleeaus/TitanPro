<?php

namespace App\Filament\TitanPro\Widgets;

use App\Models\Subscription;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ActiveOrganizationsWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Active Organizations';

    protected static ?int $sort = 1;

    public static function metrics(): array
    {
        return Cache::remember('titanpro.widgets.active-organizations', 60, function (): array {
            $previousPeriodEnd = now()->startOfMonth()->subSecond();

            $current = self::activeOrganizationCount();
            $previous = self::activeOrganizationCount($previousPeriodEnd);

            return [
                'current' => $current,
                'previous' => $previous,
                'delta' => $current - $previous,
            ];
        });
    }

    protected function getStats(): array
    {
        $metrics = self::metrics();
        $delta = $metrics['delta'];
        $deltaPrefix = $delta >= 0 ? '+' : '';

        return [
            Stat::make('Active Organizations', (string) $metrics['current'])
                ->description("{$deltaPrefix}{$delta} vs prior period")
                ->color($delta >= 0 ? 'success' : 'danger'),
        ];
    }

    private static function activeOrganizationCount($asOf = null): int
    {
        $latestSubscriptions = DB::table('subscriptions as subscriptions')
            ->selectRaw('MAX(subscriptions.id) as id')
            ->groupBy('subscriptions.organization_id');

        if ($asOf !== null) {
            $latestSubscriptions->where('subscriptions.created_at', '<=', $asOf);
        }

        return (int) DB::table('subscriptions as current_subscriptions')
            ->joinSub($latestSubscriptions, 'latest_subscriptions', function ($join): void {
                $join->on('current_subscriptions.id', '=', 'latest_subscriptions.id');
            })
            ->whereIn('current_subscriptions.status', [
                Subscription::STATUS_TRIALING,
                Subscription::STATUS_ACTIVE,
                Subscription::STATUS_PAST_DUE,
            ])
            ->count();
    }
}
