<?php

namespace Modules\TitanGoField\Filament\Pages;

use Filament\Pages\Page;

class JobChecklistPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Job Checklist';
    protected static string|\UnitEnum|null $navigationGroup = 'Field';
    protected static ?int $navigationSort = 30;
    protected string $view = 'titangofield::pages.jobchecklistpage';

    public function getTitle(): string
    {
        return 'Job Checklist';
    }
}
