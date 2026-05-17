<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ReceiptResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Budgeting\Filament\Resources\ReceiptResource;

class CreateReceipt extends CreateRecord
{
    protected static string $resource = ReceiptResource::class;
}
