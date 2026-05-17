<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ReimbursementBatchResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Budgeting\Filament\Resources\ReimbursementBatchResource;

class ListReimbursementBatches extends ListRecords
{
    protected static string $resource = ReimbursementBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
