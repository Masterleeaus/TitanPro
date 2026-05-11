<?php

namespace Modules\CleaningJobs\Providers;

use App\Platform\Modules\DashboardRegistry;
use App\Platform\Modules\SettingsRegistry as PlatformSettingsRegistry;
use App\Platform\Modules\ShortcutRegistry as PlatformShortcutRegistry;
use App\Platform\Modules\TableRegistry;
use Illuminate\Support\ServiceProvider;
use Modules\CleaningJobs\ControlPanel\Settings\SettingsRegistry;
use Modules\CleaningJobs\ControlPanel\Shortcuts\ShortcutRegistry;
use Modules\CleaningJobs\ControlPanel\Tables\TabsRegistry;

class ManifestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $module = 'CleaningJobs';

        $this->app->make(DashboardRegistry::class)->registerManifest($module, [
            'widgets' => [
                ['key' => 'cleaningjobs.dashboard.jobs_today', 'class' => \Modules\CleaningJobs\ControlPanel\Metrics\DashboardMetrics::class, 'method' => 'jobsToday'],
                ['key' => 'cleaningjobs.dashboard.overdue_jobs', 'class' => \Modules\CleaningJobs\ControlPanel\Metrics\DashboardMetrics::class, 'method' => 'overdueJobs'],
                ['key' => 'cleaningjobs.dashboard.active_cleaners', 'class' => \Modules\CleaningJobs\ControlPanel\Metrics\DashboardMetrics::class, 'method' => 'activeCleaners'],
                ['key' => 'cleaningjobs.widget.todays_jobs', 'class' => \Modules\CleaningJobs\ControlPanel\Widgets\OperationalWidgets::class, 'method' => 'todaysJobs'],
                ['key' => 'cleaningjobs.widget.pending_requests', 'class' => \Modules\CleaningJobs\ControlPanel\Widgets\OperationalWidgets::class, 'method' => 'pendingRequests'],
            ],
            'layouts' => [
                ['key' => 'cleaningjobs.control_panel', 'columns' => 2],
            ],
        ]);

        $this->app->make(TableRegistry::class)->registerManifest($module, [
            'tables' => TabsRegistry::getTabs(),
        ]);

        $this->app->make(PlatformShortcutRegistry::class)->registerManifest($module, [
            'shortcuts' => ShortcutRegistry::getShortcuts(),
        ]);

        $this->app->make(PlatformSettingsRegistry::class)->registerManifest($module, [
            'settings' => SettingsRegistry::getSettings(),
        ]);
    }
}
