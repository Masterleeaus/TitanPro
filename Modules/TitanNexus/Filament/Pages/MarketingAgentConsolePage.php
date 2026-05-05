<?php

namespace Modules\TitanNexus\Filament\Pages;

use Filament\Pages\Page;

class MarketingAgentConsolePage extends Page
{
    protected static \BackedEnum|string|null $navigationIcon = 'heroicon-o-megaphone';
    protected static \UnitEnum|string|null $navigationGroup = 'Titan Nexus';
    protected static ?string $navigationLabel = 'Marketing Agent Console';
    protected static ?string $title = 'Marketing Agent Console';
    protected static ?int $navigationSort = 30;

    protected string $view = 'titan-nexus::filament.pages.nexus-placeholder-page';

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }
}
