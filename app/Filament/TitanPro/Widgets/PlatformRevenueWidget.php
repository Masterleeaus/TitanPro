<?php

namespace App\Filament\TitanPro\Widgets;

use App\Models\Subscription;
use App\Services\PlanService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class PlatformRevenueWidget extends StatsOverviewWidget
{
    protected ?string $heading = 'Platform Revenue';

    protected static ?int $sort = 2;

    public static function metrics(): array
    {
        return Cache::remember('titanpro.widgets.platform-revenue', 60, function (): array {
            $currentMonthStart = now()->startOfMonth();
            $previousMonthStart = now()->subMonthNoOverflow()->startOfMonth();
            $previousMonthEnd = now()->startOfMonth()->subSecond();

            $mrrCurrent = self::estimatedMrr();
            $mrrPrevious = self::estimatedMrr($previousMonthEnd);

            $revenueCurrent = (float) DB::table('payments')
                ->whereBetween('paid_at', [$currentMonthStart, now()])
                ->sum('amount');

            $revenuePrevious = (float) DB::table('payments')
                ->whereBetween('paid_at', [$previousMonthStart, $previousMonthEnd])
                ->sum('amount');

            return [
                'mrr_current' => $mrrCurrent,
                'mrr_previous' => $mrrPrevious,
                'mrr_delta' => $mrrCurrent - $mrrPrevious,
                'revenue_current' => $revenueCurrent,
                'revenue_previous' => $revenuePrevious,
                'revenue_delta' => $revenueCurrent - $revenuePrevious,
            ];
        });
    }

    protected function getStats(): array
    {
        $metrics = self::metrics();

        return [
            Stat::make('MRR (estimated)', self::currency($metrics['mrr_current']))
                ->description(self::deltaDescription($metrics['mrr_delta']))
                ->color($metrics['mrr_delta'] >= 0 ? 'success' : 'danger'),
            Stat::make('Revenue this month', self::currency($metrics['revenue_current']))
                ->description(self::deltaDescription($metrics['revenue_delta']))
                ->color($metrics['revenue_delta'] >= 0 ? 'success' : 'danger'),
        ];
    }

    private static function estimatedMrr($asOf = null): float
    {
        $latestSubscriptions = DB::table('subscriptions as subscriptions')
            ->selectRaw('MAX(subscriptions.id) as id')
            ->groupBy('subscriptions.organization_id');

        if ($asOf !== null) {
            $latestSubscriptions->where('subscriptions.created_at', '<=', $asOf);
        }

        $activeSubscriptions = DB::table('subscriptions as current_subscriptions')
            ->joinSub($latestSubscriptions, 'latest_subscriptions', function ($join): void {
                $join->on('current_subscriptions.id', '=', 'latest_subscriptions.id');
            })
            ->whereIn('current_subscriptions.status', [
                Subscription::STATUS_TRIALING,
                Subscription::STATUS_ACTIVE,
                Subscription::STATUS_PAST_DUE,
            ])
            ->get(['current_subscriptions.plan', 'current_subscriptions.billing_interval']);

        $planService = app(PlanService::class);

        return (float) $activeSubscriptions->sum(function ($subscription) use ($planService): float {
            return $subscription->billing_interval === 'annual'
                ? $planService->annualPrice($subscription->plan)
                : $planService->monthlyPrice($subscription->plan);
        });
    }

    private static function currency(float|int $value): string
    {
        return '$' . number_format((float) $value, 2);
    }

    private static function deltaDescription(float|int $delta): string
    {
        $prefix = $delta >= 0 ? '+' : '-';

        return "{$prefix}" . self::currency(abs((float) $delta)) . ' vs prior period';
    }
}
