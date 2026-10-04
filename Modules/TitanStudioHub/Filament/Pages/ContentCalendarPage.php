<?php

namespace Modules\TitanStudioHub\Filament\Pages;

use Filament\Pages\Page;

class ContentCalendarPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Content Calendar';
    protected static string|\UnitEnum|null $navigationGroup = 'Studio';
    protected static ?int $navigationSort = 10;
    protected string $view = 'titanstudiohub::pages.contentcalendarpage';

    public function getTitle(): string
    {
        return 'Content Calendar';
    }
}
