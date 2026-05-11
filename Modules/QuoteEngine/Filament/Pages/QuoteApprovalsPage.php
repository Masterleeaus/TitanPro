<?php

namespace Modules\QuoteEngine\Filament\Pages;

use Filament\Pages\Page;

class QuoteApprovalsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-check-circle';
    protected static ?string $navigationLabel = 'Quote Approvals';
    protected static ?string $navigationGroup = 'Estimating';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'quoteengine::pages.quoteapprovalspage';

    public function getTitle(): string
    {
        return 'Quote Approvals';
    }
}
