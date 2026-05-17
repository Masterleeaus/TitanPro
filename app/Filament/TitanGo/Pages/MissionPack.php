<?php

namespace App\Filament\TitanGo\Pages;

use Filament\Pages\Page;

class MissionPack extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static string|\UnitEnum|null $navigationGroup = 'TitanGo';
    protected static ?string $navigationLabel = 'Offline Pack';
    protected static ?string $title = 'Offline Pack';
    protected static ?int $navigationSort = 9;
    protected static ?string $slug = 'mission-pack';
    protected string $view = 'filament.titango.pages.mission-pack';

    protected function getViewData(): array
    {
        return [
            'missionPackEndpoint' => route('titango.mission-pack', absolute: false),
            'syncEndpoint' => route('titango.sync', absolute: false),
        ];
    }
}
