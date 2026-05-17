<?php

namespace Modules\TitanProAdmin\Services;

use App\Models\Organization;

class LicenseService
{
    public function summary(int $tenantId): array
    {
        $tenant = Organization::withoutGlobalScopes()->findOrFail($tenantId);

        return [
            'tenant_id' => $tenant->getKey(),
            'plan' => $tenant->plan,
            'trial_ends_at' => $tenant->trial_ends_at,
            'suspended_at' => $tenant->suspended_at,
        ];
    }
}
