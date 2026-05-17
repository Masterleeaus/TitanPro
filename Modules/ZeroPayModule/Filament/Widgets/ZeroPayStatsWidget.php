<?php

namespace Modules\ZeroPayModule\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Modules\ZeroPayModule\Models\ZeroPayBankDeposit;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class ZeroPayStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Sessions Today', ZeroPaySession::whereDate('created_at', today())->count()),
            Stat::make('Completed Today', ZeroPaySession::completed()->whereDate('updated_at', today())->count()),
            Stat::make('Revenue Today', '$'.number_format((float) ZeroPayTransaction::whereDate('created_at', today())->sum('amount'), 2)),
            Stat::make('Pending Deposits', ZeroPayBankDeposit::pendingReview()->count())->color('warning'),
        ];
    }
}
