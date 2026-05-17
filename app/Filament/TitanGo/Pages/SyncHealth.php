<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;

class SyncHealth extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanGo';

    protected static ?string $navigationLabel = 'Sync';

    protected static ?string $title = 'Sync';

    protected static ?int $navigationSort = 8;

    protected static ?string $slug = 'sync';

    protected string $view = 'filament.titango.pages.sync-health';

    protected function getViewData(): array
    {
        return [
            'summary' => [
                'queued' => 0,
                'uploaded' => 0,
                'last_sync' => now(),
            ],
        ];
    }
}
