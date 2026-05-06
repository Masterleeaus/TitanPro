<?php

namespace Modules\CleaningJobs\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * ControlPanelServiceProvider registers the TitanWork control panel components.
 *
 * This provider is responsible for binding data providers and publishing
 * assets or resources required by the control panel UI. At this stage it
 * only serves as a placeholder to satisfy the blueprint contract.
 */
class ControlPanelServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Here you could bind interfaces to implementations for metrics,
        // widgets, shortcuts, settings, tables, etc.
    }

    public function boot(): void
    {
        // You may publish view files or routes related to the control panel here.
    }
}