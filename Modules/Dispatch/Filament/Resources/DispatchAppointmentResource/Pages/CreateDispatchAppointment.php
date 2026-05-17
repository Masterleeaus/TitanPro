<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchAppointmentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Dispatch\Filament\Resources\DispatchAppointmentResource;

class CreateDispatchAppointment extends CreateRecord
{
    protected static string $resource = DispatchAppointmentResource::class;
}
