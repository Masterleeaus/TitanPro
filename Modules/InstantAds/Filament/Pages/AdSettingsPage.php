<?php

namespace Modules\InstantAds\Filament\Pages;

use Filament\Pages\Page;

class AdSettingsPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $navigationLabel = 'InstantAds Settings';
    protected static string $view = 'instantads::filament.pages.ad-settings-page';
}
