<?php

namespace App\Filament\Resources\OrganizationSettingResource\Pages;

use App\Filament\Resources\OrganizationSettingResource;
use App\Models\OrganizationSetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationSettings extends ListRecords
{
    protected static string $resource = OrganizationSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('initializeSettings')
                ->label('Initialise settings')
                ->icon('heroicon-o-cog-6-tooth')
                ->url(OrganizationSettingResource::getUrl('create'))
                ->visible(function (): bool {
                    $organizationId = auth()->user()?->organization_id;

                    if ($organizationId === null) {
                        return false;
                    }

                    return OrganizationSetting::where('organization_id', $organizationId)->doesntExist();
                }),
        ];
    }
}
