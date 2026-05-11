<?php

namespace Modules\TitanGoField\Filament\Pages;

use Filament\Pages\Page;

class JobChecklistPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Job Checklist';
    protected static ?string $navigationGroup = 'Field';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'titangofield::pages.jobchecklistpage';

    public function getTitle(): string
    {
        return 'Job Checklist';
    }
}
