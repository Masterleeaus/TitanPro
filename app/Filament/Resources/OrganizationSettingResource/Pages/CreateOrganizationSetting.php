<?php

namespace App\Filament\Resources\OrganizationSettingResource\Pages;

use App\Filament\Resources\OrganizationSettingResource;
use App\Models\OrganizationSetting;
use Filament\Resources\Pages\CreateRecord;

class CreateOrganizationSetting extends CreateRecord
{
    protected static string $resource = OrganizationSettingResource::class;

    /**
     * Redirect to the edit page when the authenticated user's organization
     * already has a settings row, preventing duplicate records.
     */
    public function mount(): void
    {
        $orgId = auth()->user()?->organization_id;

        if ($orgId) {
            $existing = OrganizationSetting::where('organization_id', $orgId)->first();

            if ($existing) {
                $this->redirect(
                    OrganizationSettingResource::getUrl('edit', ['record' => $existing])
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
