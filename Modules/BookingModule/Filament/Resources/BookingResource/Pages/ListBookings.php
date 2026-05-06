<?php

namespace Modules\BookingModule\Filament\Resources\BookingResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\BookingModule\Filament\Resources\BookingResource;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;
}
