<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Pages;

use Filament\Pages\Page;
use Modules\Dispatch\Services\Cleaning\CleaningDispatchBoardService;

class CleaningTodayBoardPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?string $navigationGroup = 'Cleaning Dispatch';
    protected static ?string $title = 'Today\'s Cleaning Board';
    protected static string $view = 'dispatch::filament.pages.cleaning-today-board';

    public array $lanes = [];

    public function mount(CleaningDispatchBoardService $board): void
    {
        $this->lanes = $board->lanesForDate();
    }
}
