<?php

namespace Modules\Asset\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Asset\Entities\Asset;
use Modules\Asset\Policies\AssetPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Asset::class => AssetPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }
}
