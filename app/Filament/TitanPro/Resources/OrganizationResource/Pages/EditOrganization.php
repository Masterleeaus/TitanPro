<?php

namespace App\Filament\TitanPro\Resources\OrganizationResource\Pages;

use App\Filament\TitanPro\Resources\OrganizationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrganization extends EditRecord
{
    protected static string $resource = OrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
