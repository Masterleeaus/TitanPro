<?php

namespace App\Filament\TitanQuotes\Resources;

use App\Filament\TitanQuotes\Resources\EstimateResource\Pages;

class EstimateResource extends \App\Filament\Resources\EstimateResource
{
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstimates::route('/'),
            'create' => Pages\CreateEstimate::route('/create'),
            'edit' => Pages\EditEstimate::route('/{record}/edit'),
        ];
    }
}
