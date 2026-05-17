<?php

namespace App\Filament\Resources\OrganizationSettingResource\Pages;

use App\Filament\Resources\OrganizationSettingResource;
use App\Models\OrganizationSetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationSettings extends ListRecords
{
    protected static string $resource = OrganizationSettingResource::class;

    protected ?bool $hasOrganizationSettings = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('initializeSettings')
                ->label('Initialise settings')
                ->icon('heroicon-o-cog-6-tooth')
                ->url(OrganizationSettingResource::getUrl('create'))
                ->visible(fn (): bool => ! $this->hasOrganizationSettings()),
        ];
    }

    protected function hasOrganizationSettings(): bool
    {
        if ($this->hasOrganizationSettings !== null) {
            return $this->hasOrganizationSettings;
        }

        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return $this->hasOrganizationSettings = false;
        }

        return $this->hasOrganizationSettings = OrganizationSetting::where('organization_id', $organizationId)->exists();
    }
}
