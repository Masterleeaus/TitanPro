<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;
use Modules\Accountings\Services\GstReportService;

class CompliancePage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Compliance';
    protected static ?string $title = 'Compliance';
    protected static ?int $navigationSort = 222;
    protected string $view = 'accountings::filament.pages.workspace-placeholder';

    public function gstSummary(?string $from = null, ?string $to = null, string $basis = 'accrual'): array
    {
        return app(GstReportService::class)->summary($from, $to, $basis);
    }
}
