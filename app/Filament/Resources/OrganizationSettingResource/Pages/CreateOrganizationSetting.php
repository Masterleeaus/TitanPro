<?php

namespace App\Filament\Resources\OrganizationSettingResource\Pages;

use App\Filament\Resources\OrganizationSettingResource;
use App\Models\OrganizationSetting;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganizationSetting extends CreateRecord
{
    protected static string $resource = OrganizationSettingResource::class;

    /**
     * Keep the create route guarded so direct visits still land on the
     * canonical row when one already exists for the current organization.
     */
    public function mount(): void
    {
        $organizationId = auth()->user()?->organization_id;

        if ($organizationId !== null) {
            $setting = OrganizationSetting::where('organization_id', $organizationId)->first();

            if ($setting !== null) {
                $this->redirect(
                    OrganizationSettingResource::getUrl('edit', ['record' => $setting])
                );

                return;
            }
        }

        parent::mount();
    }

    /**
     * Automatically assign the current user's organization so the form does
     * not expose an editable organization_id field.
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['organization_id'] = auth()->user()?->organization_id;

        return $data;
    }
}
