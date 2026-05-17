<?php

namespace Modules\Complaint\Filament\Pages;

use Filament\Pages\Page;

class EscalationPage extends Page
{
    protected static ?string $slug = 'complaint/escalation';
    protected static ?string $navigationLabel = 'Complaint Escalation';
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-trending-up';
    protected static string|\UnitEnum|null $navigationGroup = 'ZeroFuss';

    protected string $view = 'complaint::filament.pages.escalation-page';
}
