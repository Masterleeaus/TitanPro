<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Providers;

use Filament\Panel;
use Illuminate\Support\ServiceProvider;
use Modules\CleaningJobs\Filament\Plugin\CleaningJobsPlugin;

class FilamentServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $panelId = 'groundzero';

        if (! class_exists(Panel::class)) {
            return;
        }

        $this->callAfterResolving('filament', function () use ($panelId): void {
            $panel = \Filament\Facades\Filament::getPanel($panelId, isStrict: false);

            if ($panel === null) {
                return;
            }
            $panel->plugin(CleaningJobsPlugin::make());
        });
    }
}
