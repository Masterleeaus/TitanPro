<?php

namespace Modules\ZeroPayModule\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\Policies\ZeroPaySessionPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [ZeroPaySession::class => ZeroPaySessionPolicy::class];
}
