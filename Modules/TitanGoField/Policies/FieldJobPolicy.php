<?php

namespace Modules\TitanGoField\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\TitanGoField\Models\FieldJob;

class FieldJobPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $this->hasCompany($user);
    }

    public function view(User $user, FieldJob $job): bool
    {
        return $this->sameCompany($user, $job);
    }

    public function create(User $user): bool
    {
        return $this->hasCompany($user);
    }

    public function update(User $user, FieldJob $job): bool
    {
        return $this->sameCompany($user, $job);
    }

    public function delete(User $user, FieldJob $job): bool
    {
        return $this->sameCompany($user, $job);
    }

    public function start(User $user, FieldJob $job): bool
    {
        if (! $this->sameCompany($user, $job)) {
            return false;
        }

        // Compliance chain: permits and inspections must pass if tables exist
        if (\Illuminate\Support\Facades\Schema::hasTable('field_job_permits')) {
            $hasApprovedPermit = $job->permits()->where('status', 'approved')->exists();
            if (! $hasApprovedPermit && $job->permits()->exists()) {
                return false;
            }
        }

        return true;
    }

    public function complete(User $user, FieldJob $job): bool
    {
        return $this->sameCompany($user, $job);
    }

    public function invoice(User $user, FieldJob $job): bool
    {
        return $this->sameCompany($user, $job);
    }

    public function approve(User $user, FieldJob $job): bool
    {
        return $this->sameCompany($user, $job);
    }

    // --- Helpers ---
    private function hasCompany(User $user): bool
    {
        return ! empty($user->company_id);
    }

    private function sameCompany(User $user, FieldJob $job): bool
    {
        return $this->hasCompany($user) && (int) $user->company_id === (int) $job->company_id;
    }
}
