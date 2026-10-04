<?php

namespace Modules\TitanStudioHub\Filament\Pages;

use Filament\Pages\Page;

class BrandLibraryPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';
    protected static ?string $navigationLabel = 'Brand Library';
    protected static string|\UnitEnum|null $navigationGroup = 'Studio';
    protected static ?int $navigationSort = 20;
    protected string $view = 'titanstudiohub::pages.brandlibrarypage';

    public function getTitle(): string
    {
        return 'Brand Library';
    }
}
