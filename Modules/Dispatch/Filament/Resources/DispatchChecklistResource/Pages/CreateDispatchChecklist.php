<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchChecklistResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\DispatchChecklistResource;

class CreateDispatchChecklist extends CreateRecord
{
    protected static string $resource = DispatchChecklistResource::class;
}
