<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\TechnicianProfileResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\TechnicianProfileResource;

class ListTechnicianProfiles extends ListRecords
{
    protected static string $resource = TechnicianProfileResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
