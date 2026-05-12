<?php

namespace Modules\Asset\Policies;

use App\Models\User;
use Modules\Asset\Entities\Asset;

class AssetPolicy
{
    public function view(User $user, Asset $asset): bool
    {
        return $this->isTenantMatch($user, $asset) && $this->canByPermission($user, 'view_asset');
    }

    public function update(User $user, Asset $asset): bool
    {
        return $this->isTenantMatch($user, $asset) && $this->canByPermission($user, 'edit_asset');
    }

    private function isTenantMatch(User $user, Asset $asset): bool
    {
        $userTenantId = (int) ($user->company_id ?? $user->organization_id ?? 0);
        $assetTenantId = (int) ($asset->company_id ?? 0);

        return $userTenantId > 0 && $assetTenantId > 0 && $userTenantId === $assetTenantId;
    }

    private function canByPermission(User $user, string $permission): bool
    {
        if (! method_exists($user, 'permission')) {
            return true;
        }

        return $user->permission($permission) !== 'none';
    }
}
