<?php

namespace Modules\BookingModule\Filament\Resources\AppointmentResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\BookingModule\Filament\Resources\AppointmentResource;

class EditAppointment extends EditRecord
{
    protected static string $resource = AppointmentResource::class;
}
