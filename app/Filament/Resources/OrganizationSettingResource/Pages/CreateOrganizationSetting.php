<?php

namespace App\Filament\Resources\OrganizationSettingResource\Pages;

use App\Filament\Resources\OrganizationSettingResource;
use App\Models\OrganizationSetting;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganizationSetting extends CreateRecord
{
    protected static string $resource = OrganizationSettingResource::class;

    public function mount(): void
    {
        $organizationId = auth()->user()?->organization_id;

        abort_if($organizationId === null, 404);

        $setting = OrganizationSetting::firstOrCreateForOrganization($organizationId);

        $this->redirect(
            OrganizationSettingResource::getUrl('edit', ['record' => $setting])
        );
    }
}
