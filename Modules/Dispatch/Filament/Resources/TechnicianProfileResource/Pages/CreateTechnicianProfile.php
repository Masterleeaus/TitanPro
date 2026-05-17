<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\TechnicianProfileResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\TechnicianProfileResource;

class CreateTechnicianProfile extends CreateRecord
{
    protected static string $resource = TechnicianProfileResource::class;
}
