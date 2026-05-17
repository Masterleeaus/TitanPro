<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ReceiptResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Modules\Budgeting\Filament\Resources\ReceiptResource;

class EditReceipt extends EditRecord
{
    protected static string $resource = ReceiptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
