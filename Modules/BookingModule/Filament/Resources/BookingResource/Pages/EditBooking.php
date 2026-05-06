<?php

namespace Modules\BookingModule\Filament\Resources\BookingResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\BookingModule\Filament\Resources\BookingResource;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;
}
