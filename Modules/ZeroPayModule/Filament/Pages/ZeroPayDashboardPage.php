<?php

namespace Modules\ZeroPayModule\Filament\Pages;

use Filament\Pages\Page;
use Modules\ZeroPayModule\Models\ZeroPayBankDeposit;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Models\ZeroPayTransaction;

class ZeroPayDashboardPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationGroup = 'ZeroPay';

    protected static ?string $navigationLabel = 'Dashboard';

    protected static ?string $title = 'ZeroPay Dashboard';

    protected static ?int $navigationSort = 0;

    protected static string $view = 'zeropay-module::filament.pages.zeropay-dashboard';

    public int $totalSessions = 0;

    public int $completedSessions = 0;

    public float $totalRevenue = 0.0;

    public int $pendingDeposits = 0;

    public function mount(): void
    {
        $this->totalSessions = ZeroPaySession::query()->count();
        $this->completedSessions = ZeroPaySession::query()->where('status', 'completed')->count();
        $this->totalRevenue = (float) ZeroPayTransaction::query()->where('status', 'completed')->sum('net_amount');
        $this->pendingDeposits = ZeroPayBankDeposit::query()->where('status', 'pending_review')->count();
    }

    public static function canAccess(): bool
    {
        return auth()->user()?->can('zeropay.view') ?? false;
    }
}
