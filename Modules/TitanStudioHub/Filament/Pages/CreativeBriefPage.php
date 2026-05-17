<?php

namespace Modules\TitanStudioHub\Filament\Pages;

use Filament\Pages\Page;

class CreativeBriefPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?string $navigationLabel = 'Creative Briefs';
    protected static ?string $navigationGroup = 'Studio';
    protected static ?int $navigationSort = 40;
    protected static string $view = 'titanstudiohub::pages.creativebriefpage';

    public function getTitle(): string
    {
        return 'Creative Briefs';
    }
}
