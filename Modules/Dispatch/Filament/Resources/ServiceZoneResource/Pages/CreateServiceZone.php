<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\ServiceZoneResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\ServiceZoneResource;

class CreateServiceZone extends CreateRecord
{
    protected static string $resource = ServiceZoneResource::class;
}
