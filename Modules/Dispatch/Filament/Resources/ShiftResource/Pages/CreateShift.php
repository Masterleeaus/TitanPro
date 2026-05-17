<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\ShiftResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\ShiftResource;

class CreateShift extends CreateRecord
{
    protected static string $resource = ShiftResource::class;
}
