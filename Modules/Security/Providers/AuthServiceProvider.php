<?php

namespace Modules\Security\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Security\Entities\CardItems;
use Modules\Security\Entities\Security;
use Modules\Security\Entities\TrAccessCard;
use Modules\Security\Entities\TrInOutPermit;
use Modules\Security\Entities\WorkPermits;
use Modules\Security\Entities\WorkPermitsFile;
use Modules\Security\Policies\RBAC\SecurityModulePolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Security::class => SecurityModulePolicy::class,
        TrInOutPermit::class => SecurityModulePolicy::class,
        WorkPermits::class => SecurityModulePolicy::class,
        WorkPermitsFile::class => SecurityModulePolicy::class,
        TrAccessCard::class => SecurityModulePolicy::class,
        CardItems::class => SecurityModulePolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
