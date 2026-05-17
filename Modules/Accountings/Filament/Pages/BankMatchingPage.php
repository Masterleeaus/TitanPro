<?php

namespace Modules\Accountings\Filament\Pages;

use Filament\Pages\Page;
use Modules\Accountings\Tools\MatchBankDepositTool;

class BankMatchingPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-link';
    protected static string|\UnitEnum|null $navigationGroup = 'Finance';
    protected static ?string $navigationLabel = 'Bank Matching';
    protected static ?string $title = 'Bank Matching';
    protected static ?int $navigationSort = 221;
    protected string $view = 'accountings::filament.pages.workspace-placeholder';

    public function match(array $deposit, array $candidateInvoices = []): array
    {
        return app(MatchBankDepositTool::class)($deposit, $candidateInvoices);
    }
}
