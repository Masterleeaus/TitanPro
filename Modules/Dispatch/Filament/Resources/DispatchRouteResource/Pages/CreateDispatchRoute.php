<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchRouteResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\DispatchRouteResource;

class CreateDispatchRoute extends CreateRecord
{
    protected static string $resource = DispatchRouteResource::class;
}
