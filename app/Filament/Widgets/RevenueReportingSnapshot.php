<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class RevenueReportingSnapshot extends Widget
{
    protected string $view = 'filament.widgets.revenue-reporting-snapshot';

    protected int|string|array $columnSpan = 'full';

    protected static ?int $sort = 3;
}
