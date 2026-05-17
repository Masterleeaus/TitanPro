<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchWorkOrderResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\DispatchWorkOrderResource;

class ListDispatchWorkOrders extends ListRecords
{
    protected static string $resource = DispatchWorkOrderResource::class;
}
