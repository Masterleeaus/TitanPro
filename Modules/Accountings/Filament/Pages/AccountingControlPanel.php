<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;
use Modules\Accountings\UI\ControlPanel\ControlPanelLayout;
use Modules\Accountings\UI\Tabs\ControlPanelTabs;

class AccountingControlPanel extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Money Manager';
    protected static ?string $title = 'Titan Money Panel';
    protected static ?string $slug = 'zeropay-accounting-workspace';
    protected static ?int $navigationSort = 211;
    protected static string $view = 'accountings::filament.pages.control-panel';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return (bool) (
            $user?->can('money.view')
            || $user?->can('accounting.view')
            || $user?->can('accountings.view')
        );
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function getViewData(): array
    {
        return [
            'module' => 'accountings',
            'title' => 'Titan Money Panel',
            'label' => 'Money Manager',
            'tabs' => ControlPanelTabs::make(),
            'sections' => ControlPanelLayout::sections(),
            'metrics' => ControlPanelLayout::metrics(),
            'records' => ControlPanelLayout::records(),
            'agent' => 'MoneyAgent',
        ];
    }
}
