<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchAppointmentResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Dispatch\Filament\Resources\DispatchAppointmentResource;

class ListDispatchAppointments extends ListRecords
{
    protected static string $resource = DispatchAppointmentResource::class;
}
