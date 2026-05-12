<?php
declare(strict_types=1);
namespace Modules\CleaningJobs\Filament\Plugin;

use Filament\Contracts\Plugin;
use Filament\Panel;

class CleaningJobsPlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
    }

    public function getId(): string
    {
        return 'cleaningjobs';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                \Modules\CleaningJobs\Filament\Resources\CleaningJobResource::class,
                \Modules\CleaningJobs\Filament\Resources\ClientResource::class,
                \Modules\CleaningJobs\Filament\Resources\RecurringJobResource::class,
            ])
            ->pages([
                \Modules\CleaningJobs\Filament\Pages\JobSchedulePage::class,
                \Modules\CleaningJobs\Filament\Pages\TitanWorkControlPanel::class,
            ])
            ->widgets([
                \Modules\CleaningJobs\Filament\Widgets\JobBillingSummaryWidget::class,
                \Modules\CleaningJobs\Filament\Widgets\LiveJobBoardWidget::class,
            ]);
    }

    public function boot(Panel $panel): void {}
}
