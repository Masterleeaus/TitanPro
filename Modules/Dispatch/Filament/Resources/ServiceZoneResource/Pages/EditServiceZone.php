<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\ServiceZoneResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\ServiceZoneResource;

class EditServiceZone extends EditRecord
{
    protected static string $resource = ServiceZoneResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
