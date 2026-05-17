<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\CRMCore\Models\CRMCoreActivityLog;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\Lead;
use Modules\CRMCore\Policies\CRMCoreActivityLogPolicy;
use Modules\CRMCore\Policies\DealPolicy;
use Modules\CRMCore\Policies\LeadPolicy;

class PolicyServiceProvider extends ServiceProvider
{
    protected $policies = [
        Lead::class => LeadPolicy::class,
        Deal::class => DealPolicy::class,
        CRMCoreActivityLog::class => CRMCoreActivityLogPolicy::class,
    ];

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
