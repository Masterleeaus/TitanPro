<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament\Resources\ApprovalThresholdResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Budgeting\Filament\Resources\ApprovalThresholdResource;

class ListApprovalThresholds extends ListRecords
{
    protected static string $resource = ApprovalThresholdResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
