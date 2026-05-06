<?php

namespace Modules\Accountings\Filament\Widgets;

class AccountingKpiWidget
{
    public static function metrics(): array
    {
        return \Modules\Accountings\UI\ControlPanel\ControlPanelLayout::metrics();
    }
}
