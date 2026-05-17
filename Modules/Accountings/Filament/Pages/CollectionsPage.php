<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;
use Modules\EInvoice\Actions\GenerateLateInvoiceFollowupAction;
use Modules\EInvoice\Entities\Invoice;

class CollectionsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Collections';
    protected static ?string $title = 'Collections';
    protected static ?int $navigationSort = 220;
    protected string $view = 'accountings::filament.pages.workspace-placeholder';

    public function generateFollowup(Invoice $invoice, int $daysOverdue = 7): array
    {
        return app(GenerateLateInvoiceFollowupAction::class)->execute($invoice, $daysOverdue);
    }
}
