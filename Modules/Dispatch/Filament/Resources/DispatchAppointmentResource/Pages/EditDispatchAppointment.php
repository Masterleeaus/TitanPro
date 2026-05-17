<?php

declare(strict_types=1);

namespace Modules\Dispatch\Filament\Resources\DispatchAppointmentResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Dispatch\Filament\Resources\DispatchAppointmentResource;

class EditDispatchAppointment extends EditRecord
{
    protected static string $resource = DispatchAppointmentResource::class;
}
