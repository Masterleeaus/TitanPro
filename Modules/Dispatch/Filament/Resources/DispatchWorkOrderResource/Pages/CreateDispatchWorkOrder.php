<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchWorkOrderResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\DispatchWorkOrderResource;

class CreateDispatchWorkOrder extends CreateRecord
{
    protected static string $resource = DispatchWorkOrderResource::class;
}
