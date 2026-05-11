<?php

namespace App\Extensions\TitanCommand\System\JobManager\Policies;

use App\Models\User;
use App\Extensions\TitanCommand\System\JobManager\Entities\JobManagerSetting;






class JobManagerSettingPolicy
{
    protected \App\Extensions\TitanCommand\System\JobManager\Policies\WorkOrderPolicy $base;

    public function __construct()
    {
        $this->base = new \App\Extensions\TitanCommand\System\JobManager\Policies\WorkOrderPolicy();
    }

    public function viewAny(User $user): bool { return $this->base->viewAny($user); }
    public function view(User $user, JobManagerSetting $m): bool { return $this->base->view($user, $m->workOrder ?? $m); }
    public function create(User $user): bool { return $this->base->create($user); }
    public function update(User $user, JobManagerSetting $m): bool { return $this->base->update($user, $m->workOrder ?? $m); }
    public function delete(User $user, JobManagerSetting $m): bool { return $this->base->delete($user, $m->workOrder ?? $m); }
    public function manageSettings(User $user): bool { return $this->base->manageSettings($user); }
}
