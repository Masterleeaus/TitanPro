<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\ShiftResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\ShiftResource;

class ListShifts extends ListRecords
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
