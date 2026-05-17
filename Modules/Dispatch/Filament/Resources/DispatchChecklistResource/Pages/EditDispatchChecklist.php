<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchChecklistResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\DispatchChecklistResource;

class EditDispatchChecklist extends EditRecord
{
    protected static string $resource = DispatchChecklistResource::class;
}
