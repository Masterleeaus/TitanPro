<?php

namespace App\Filament\Resources\EstimateResource\Pages;

use App\Filament\Resources\EstimateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstimates extends ListRecords
{
    protected static string $resource = EstimateResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make(),
        ];

        if (class_exists(\pxlrbt\FilamentExcel\Actions\Pages\ExportAction::class)) {
            $actions[] = \pxlrbt\FilamentExcel\Actions\Pages\ExportAction::make()
                ->exports([
                    \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                        ->withFilename('estimates-' . now()->format('Y-m-d'))
                        ->fromTable(),
                ]);
        }

        return $actions;
    }
}
