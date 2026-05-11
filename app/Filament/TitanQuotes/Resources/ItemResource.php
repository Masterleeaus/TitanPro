<?php

namespace App\Filament\TitanQuotes\Resources;

use App\Filament\TitanQuotes\Resources\ItemResource\Pages;

class ItemResource extends \App\Filament\Resources\ItemResource
{
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
