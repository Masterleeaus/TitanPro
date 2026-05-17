<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchStatusLogResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\DispatchStatusLogResource;

class ListDispatchStatusLogs extends ListRecords
{
    protected static string $resource = DispatchStatusLogResource::class;
}
