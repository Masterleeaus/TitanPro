<?php

namespace Modules\GroundZeroOps\Filament\Pages;

use Filament\Pages\Page;

class LiveDispatchBoard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationLabel = 'Live Dispatch Board';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 10;
    protected static string $view = 'groundzeroops::pages.livedispatchboard';

    public function getTitle(): string
    {
        return 'Live Dispatch Board';
    }
}
