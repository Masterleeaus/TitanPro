<?php

namespace Modules\TitanGoField\Filament\Pages;

use Filament\Pages\Page;

class FieldPhotoLogPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-camera';
    protected static ?string $navigationLabel = 'Photo Log';
    protected static string|\UnitEnum|null $navigationGroup = 'Field';
    protected static ?int $navigationSort = 40;
    protected string $view = 'titangofield::pages.fieldphotologpage';

    public function getTitle(): string
    {
        return 'Photo Log';
    }
}
