<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\TechnicianProfileResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\TechnicianProfileResource;

class EditTechnicianProfile extends EditRecord
{
    protected static string $resource = TechnicianProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\DeleteAction::make()];
    }
}
