<?php

namespace Modules\EInvoice\Filament\Pages;

use Filament\Pages\Page;
use Modules\EInvoice\UI\ControlPanel\ControlPanelLayout;
use Modules\EInvoice\UI\Tabs\ControlPanelTabs;

class InvoiceControlPanel extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Money Manager';
    protected static ?string $title = 'Titan Money Panel';
    protected static ?string $slug = 'zeropay-panel';
    protected static ?int $navigationSort = 210;
    protected static string $view = 'einvoice::filament.pages.control-panel';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return (bool) (
            $user?->can('money.view')
            || $user?->can('einvoice.view')
            || $user?->can('accounting.view')
            || $user?->can('accountings.view')
        );
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getViewData(): array
    {
        return [
            'module' => 'money-manager',
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
