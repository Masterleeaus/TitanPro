<?php

namespace Modules\CRMCore\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CRMCore\Interfaces\PipelineMetricProvider;
use Modules\CRMCore\Repositories\PipelineRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PipelineMetricProvider::class, PipelineRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
