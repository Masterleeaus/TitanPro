<?php

namespace Modules\TitanNexus\Filament\Pages;

use Filament\Pages\Page;

class JobAssistPage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-briefcase';
    protected static \UnitEnum|string|null $navigationGroup = 'Titan Nexus';
    protected static ?string $navigationLabel = 'Job Assist';
    protected static ?string $title = 'Job Assist';
    protected static ?int $navigationSort = 50;

    protected string $view = 'titan-nexus::filament.pages.nexus-placeholder-page';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
