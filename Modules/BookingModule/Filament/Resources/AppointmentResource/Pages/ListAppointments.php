<?php

namespace Modules\BookingModule\Filament\Resources\AppointmentResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\BookingModule\Filament\Resources\AppointmentResource;

class ListAppointments extends ListRecords
{
    protected static string $resource = AppointmentResource::class;
}
