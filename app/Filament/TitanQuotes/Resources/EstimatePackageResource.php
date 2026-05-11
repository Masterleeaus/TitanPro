<?php

namespace App\Filament\TitanQuotes\Resources;

use App\Filament\TitanQuotes\Resources\EstimatePackageResource\Pages;

class EstimatePackageResource extends \App\Filament\Resources\EstimatePackageResource
{
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEstimatePackages::route('/'),
            'create' => Pages\CreateEstimatePackage::route('/create'),
            'edit' => Pages\EditEstimatePackage::route('/{record}/edit'),
        ];
    }
}
