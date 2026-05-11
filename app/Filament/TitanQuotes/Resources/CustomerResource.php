<?php

namespace App\Filament\TitanQuotes\Resources;

use App\Filament\TitanQuotes\Resources\CustomerResource\Pages;

class CustomerResource extends \App\Filament\Resources\CustomerResource
{
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(mixed $record): bool
    {
        return false;
    }

    public static function canDelete(mixed $record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
        ];
    }
}
