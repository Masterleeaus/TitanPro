<?php

namespace Modules\BookingModule\Filament\Resources\AppointmentResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\BookingModule\Filament\Resources\AppointmentResource;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;
}
