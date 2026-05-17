<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\ShiftResource\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\ShiftResource;

class EditShift extends EditRecord
{
    protected static string $resource = ShiftResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
