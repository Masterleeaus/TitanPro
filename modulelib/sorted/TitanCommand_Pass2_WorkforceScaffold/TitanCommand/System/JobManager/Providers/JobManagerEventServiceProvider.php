<?php

namespace App\Extensions\TitanCommand\System\JobManager\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class JobManagerEventServiceProvider extends ServiceProvider
{
    protected $listen = [];
    public function boot(): void { parent::boot(); }
}
