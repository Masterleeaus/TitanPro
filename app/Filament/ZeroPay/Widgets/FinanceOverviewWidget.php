<?php

namespace App\Filament\ZeroPay\Widgets;

use App\Support\CleaningAdminMetrics;
use Filament\Widgets\Widget;

class FinanceOverviewWidget extends Widget
{
    protected string $view = 'filament.zeropay.widgets.finance-overview-widget';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 1;

    protected function getViewData(): array
    {
        $totals = CleaningAdminMetrics::dashboardTotals();
        $statusBreakdown = CleaningAdminMetrics::invoiceStatusBreakdown();

        $recentPayments = CleaningAdminMetrics::payments()
            ->with('invoice')
            ->orderByDesc('paid_at')
            ->limit(5)
            ->get();

        return [
            'totals' => $totals,
            'statusBreakdown' => $statusBreakdown,
            'recentPayments' => $recentPayments,
        ];
    }
}
