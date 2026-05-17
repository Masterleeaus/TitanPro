<?php

namespace Modules\ZeroPayModule\Filament\Pages;

use Filament\Pages\Page;

class ZeroPayModulePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $navigationGroup = 'Operations';

    protected static ?string $navigationLabel = 'Example Module';

    protected static ?string $title = 'Example Module';

    protected static ?int $navigationSort = 100;

    protected static string $view = 'zeropay-module::filament.pages.zeropay-module-page';

    public static function canAccess(): bool
    {
        return auth()->user()?->can('zeropay.view') ?? false;
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
