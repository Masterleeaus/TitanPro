<?php

namespace App\Filament\TitanNexus\Resources\BookingHandoffResource\Pages;

use App\Filament\TitanNexus\Resources\BookingHandoffResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBookingHandoff extends EditRecord
{
    protected static string $resource = BookingHandoffResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
