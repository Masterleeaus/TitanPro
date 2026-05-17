<?php

namespace Modules\InstantAds\Filament\Pages;

use Filament\Pages\Page;

class BatchVariantPage extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-plus';
    protected static ?string $navigationLabel = 'Batch Variants';
    protected static string $view = 'instantads::filament.pages.batch-variant-page';
}
