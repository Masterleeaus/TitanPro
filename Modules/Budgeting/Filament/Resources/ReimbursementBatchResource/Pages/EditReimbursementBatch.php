<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ReimbursementBatchResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Budgeting\Filament\Resources\ReimbursementBatchResource;

class EditReimbursementBatch extends EditRecord
{
    protected static string $resource = ReimbursementBatchResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
