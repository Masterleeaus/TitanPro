<?php

namespace App\Filament\ZeroFuss\Resources\BookingResource\Pages;

use App\Filament\ZeroFuss\Resources\BookingResource;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;
}
