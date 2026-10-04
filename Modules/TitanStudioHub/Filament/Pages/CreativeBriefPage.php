<?php

namespace Modules\TitanStudioHub\Filament\Pages;

use Filament\Pages\Page;

class CreativeBriefPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Creative Briefs';
    protected static string|\UnitEnum|null $navigationGroup = 'Studio';
    protected static ?int $navigationSort = 40;
    protected string $view = 'titanstudiohub::pages.creativebriefpage';

    public function getTitle(): string
    {
        return 'Creative Briefs';
    }
}
