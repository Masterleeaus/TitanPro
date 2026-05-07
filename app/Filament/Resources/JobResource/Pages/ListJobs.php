<?php

namespace App\Filament\Resources\JobResource\Pages;

use App\Filament\Resources\JobResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListJobs extends ListRecords
{
    protected static string $resource = JobResource::class;

    protected function getHeaderActions(): array
    {
        $actions = [
            CreateAction::make()->label('Create Job'),
        ];

        if (class_exists(\pxlrbt\FilamentExcel\Actions\Pages\ExportAction::class)) {
            $actions[] = \pxlrbt\FilamentExcel\Actions\Pages\ExportAction::make()
                ->exports([
                    \pxlrbt\FilamentExcel\Exports\ExcelExport::make()
                        ->withFilename('jobs-' . now()->format('Y-m-d'))
                        ->fromTable(),
                ]);
        }

        return $actions;
    }
}
