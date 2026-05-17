<?php

namespace Modules\TitanDocs\Filament\Pages;

use Filament\Pages\Page;

class TitanDocsControlPanel extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';

    protected static \UnitEnum|string|null $navigationGroup = 'Documents';

    protected static ?string $navigationLabel = 'TitanDocs';

    protected static ?string $title = 'TitanDocs';

    protected static ?string $slug = 'titan-docs';

    protected static ?int $navigationSort = 120;

    protected string $view = 'titandocs::filament.pages.control-panel';
}
