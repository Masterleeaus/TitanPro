<?php

namespace App\Filament\Resources\InvoiceResource\Pages;

use App\Filament\Resources\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoices extends ListRecords
{
    protected static string $resource = InvoiceResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            Actions\CreateAction::make(),
        ];

        if (class_exists(\pxlrbt\FilamentExcel\Actions\Pages\ExportAction::class)) {
            $actions[] = \pxlrbt\FilamentExcel\Actions\Pages\ExportAction::make()
                ->exports([
                    \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                        ->withFilename('invoices-' . now()->format('Y-m-d'))
                        ->fromTable(),
                ]);
        }

        return $actions;
    }
}
