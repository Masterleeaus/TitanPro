<?php

declare(strict_types=1);

namespace Modules\Dispatch\Jobs\Queued;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Dispatch\Models\DispatchRoute;
use Modules\Dispatch\Services\Routing\RouteOptimisationService;

class RecalculateDispatchRouteJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public int $routeId) {}

    public function handle(RouteOptimisationService $service): void
    {
        $route = DispatchRoute::query()->find($this->routeId);

        if ($route) {
            $service->resequenceRoute($route);
        }
    }
}
