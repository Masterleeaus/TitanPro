<?php

namespace Modules\Accountings\Filament\Widgets;

use Filament\Widgets\Widget;
use Modules\Accountings\UI\ControlPanel\ControlPanelLayout;

class AccountingOverviewWidget extends Widget
{
    protected static ?string $heading = 'Accounting Overview';
    protected static ?int $sort = 10;
    protected string $view = 'accountings::filament.widgets.accounting-overview-widget';

    protected function getViewData(): array
    {
        return [
            'metrics' => ControlPanelLayout::metrics(),
        ];
    }
}
