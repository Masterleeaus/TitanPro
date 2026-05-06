<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\CleaningJobs\Models\WorkOrder;
use Modules\CleaningJobs\Policies\WorkOrderPolicy;

class PolicyServiceProvider extends ServiceProvider
{
    public function boot(): void { Gate::policy(WorkOrder::class, WorkOrderPolicy::class); }
}
