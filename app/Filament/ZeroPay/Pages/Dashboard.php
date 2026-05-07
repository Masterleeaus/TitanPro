<?php

namespace App\Filament\ZeroPay\Pages;

use App\Filament\ZeroPay\Widgets\FinanceOverviewWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $title = 'ZeroPay Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    public function getWidgets(): array
    {
        return [
            FinanceOverviewWidget::class,
        ];
    }
}
