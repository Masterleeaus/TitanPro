<?php

declare(strict_types=1);

namespace Modules\Dispatch\Actions\Allocation;

use Modules\Dispatch\Models\DispatchRoute;

class AssignTechnicianToRouteAction
{
    public function execute(DispatchRoute $route, int $technicianId): DispatchRoute
    {
        $route->forceFill(['technician_id' => $technicianId])->save();

        return $route->refresh();
    }
}
