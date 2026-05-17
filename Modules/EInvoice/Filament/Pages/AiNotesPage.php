<?php

namespace Modules\EInvoice\Filament\Pages;

use Filament\Pages\Page;
use Modules\EInvoice\Entities\EInvoiceNote;
use Modules\EInvoice\Entities\Invoice;

/**
 * Filament page for browsing and managing AI-generated notes per invoice.
 * Business mutations use Action classes only — no inline logic.
 */
class AiNotesPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-sparkles';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'AI Invoice Notes';
    protected static ?string $title = 'AI Invoice Notes';
    protected static ?string $slug = 'einvoice-ai-notes';
    protected static ?int $navigationSort = 205;
    protected string $view = 'einvoice::filament.pages.ai-notes';

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('einvoice.agent.use');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public function getViewData(): array
    {
        $invoices = Invoice::query()
            ->with(['aiNotes' => fn ($q) => $q->latest()->limit(1)])
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return [
            'invoices' => $invoices,
        ];
    }
}
