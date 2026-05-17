<?php

namespace App\Filament\Resources\OrganizationSettingResource\Pages;

use App\Filament\Resources\OrganizationSettingResource;
use App\Models\OrganizationSetting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationSettings extends ListRecords
{
    protected static string $resource = OrganizationSettingResource::class;

    protected ?bool $cachedHasOrganizationSettings = null;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('initializeSettings')
                ->label('Initialise settings')
                ->icon('heroicon-o-cog-6-tooth')
                ->action(function () {
                    $organizationId = auth()->user()?->organization_id;

                    abort_if($organizationId === null, 404);

                    $setting = OrganizationSetting::firstOrCreateForOrganization($organizationId);

                    return redirect()->to(
                        OrganizationSettingResource::getUrl('edit', ['record' => $setting])
                    );
                })
                ->visible(fn (): bool => ! $this->hasOrganizationSettings()),
        ];
    }

    protected function hasOrganizationSettings(): bool
    {
        if ($this->cachedHasOrganizationSettings !== null) {
            return $this->cachedHasOrganizationSettings;
        }

        $organizationId = auth()->user()?->organization_id;

        if ($organizationId === null) {
            return $this->cachedHasOrganizationSettings = false;
        }

        return $this->cachedHasOrganizationSettings = OrganizationSetting::where('organization_id', $organizationId)->exists();
    }
}
