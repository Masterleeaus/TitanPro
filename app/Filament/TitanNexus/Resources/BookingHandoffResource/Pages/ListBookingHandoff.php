<?php

namespace App\Filament\TitanNexus\Resources\BookingHandoffResource\Pages;

use App\Filament\TitanNexus\Resources\BookingHandoffResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookingHandoff extends ListRecords
{
    protected static string $resource = BookingHandoffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
