<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [CreateAction::make()];

        if (class_exists(\pxlrbt\FilamentExcel\Actions\Pages\ExportAction::class)) {
            $actions[] = \pxlrbt\FilamentExcel\Actions\Pages\ExportAction::make()
                ->exports([
                    \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                        ->withFilename('customers-' . now()->format('Y-m-d'))
                        ->fromTable(),
                ]);
        }

        return $actions;
    }
}
