<?php

namespace Modules\ZeroFussPortal\Policies;

use App\Models\User;

class PortalPolicy
{
    public function view(User $user, mixed $record): bool
    {
        return $this->canAccess($user, $record);
    }

    public function update(User $user, mixed $record): bool
    {
        return $this->canAccess($user, $record);
    }

    public function delete(User $user, mixed $record): bool
    {
        return $this->canAccess($user, $record);
    }

    public function create(User $user): bool
    {
        return ($user->getAuthIdentifier() ?? null) !== null;
    }

    private function canAccess(User $user, mixed $record): bool
    {
        $recordCustomerId = (int) ($record->customer_id ?? 0);
        $recordCompanyId = (int) ($record->company_id ?? 0);
        $actorCustomerId = (int) ($user->getAuthIdentifier() ?? 0);
        $actorCompanyId = (int) ($user->company_id ?? $user->organization_id ?? 0);

        return $actorCustomerId === $recordCustomerId
            && $actorCompanyId === $recordCompanyId;
    }
}
