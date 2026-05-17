<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchWorkOrderResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\DispatchWorkOrderResource;

class EditDispatchWorkOrder extends EditRecord
{
    protected static string $resource = DispatchWorkOrderResource::class;
}
