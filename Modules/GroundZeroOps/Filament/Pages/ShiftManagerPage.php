<?php

namespace Modules\GroundZeroOps\Filament\Pages;

use Filament\Pages\Page;
use Modules\GroundZeroOps\Actions\EndShiftAction;
use Modules\GroundZeroOps\Actions\StartShiftAction;
use Modules\GroundZeroOps\Models\Shift;

class ShiftManagerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-clock';
    protected static ?string $navigationLabel = 'Shift Manager';
    protected static ?string $navigationGroup = 'Operations';
    protected static ?int $navigationSort = 30;
    protected static string $view = 'groundzeroops::pages.shiftmanagerpage';

    public function getTitle(): string
    {
        return 'Shift Manager';
    }

    public function startShift(int $technicianId): void
    {
        app(StartShiftAction::class)->execute(
            technicianId: $technicianId,
            actorId: (int) (auth()->id() ?? 0),
            companyId: (int) (auth()->user()?->company_id ?? 0),
        );
    }

    public function endShift(int $shiftId): void
    {
        $shift = Shift::query()->findOrFail($shiftId);

        app(EndShiftAction::class)->execute(
            shift: $shift,
            actorId: (int) (auth()->id() ?? 0),
        );
    }
}
