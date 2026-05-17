<?php

declare(strict_types=1);

namespace Modules\Dispatch\Policies\RBAC;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Modules\Dispatch\Models\DispatchWorkOrder;

class DispatchWorkOrderPolicy
{
    public function viewAny(Authenticatable $user): bool { return $this->can($user, 'dispatch.work_orders.view'); }
    public function view(Authenticatable $user, DispatchWorkOrder $workOrder): bool { return $this->can($user, 'dispatch.work_orders.view'); }
    public function create(Authenticatable $user): bool { return $this->can($user, 'dispatch.work_orders.create'); }
    public function update(Authenticatable $user, DispatchWorkOrder $workOrder): bool { return $this->can($user, 'dispatch.work_orders.update'); }
    public function delete(Authenticatable $user, DispatchWorkOrder $workOrder): bool { return $this->can($user, 'dispatch.work_orders.delete'); }

    private function can(Authenticatable $user, string $permission): bool
    {
        return method_exists($user, 'can') ? (bool) $user->can($permission) : false;
    }
}
