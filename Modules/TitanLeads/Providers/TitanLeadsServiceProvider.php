<?php

namespace Modules\TitanLeads\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\TitanLeads\Services\InboxService;
use Modules\TitanLeads\Services\Outbox\OutboxService;
use Modules\TitanLeads\Services\InboundIngestService;

class TitanLeadsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(InboxService::class);
        $this->app->singleton(OutboxService::class);
        $this->app->singleton(InboundIngestService::class);
    }

    public function boot(): void
    {
        if (is_dir(__DIR__ . '/../Database/Migrations')) {
            $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        }

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\TitanLeads\Console\Commands\RunInvoiceFollowupsCommand::class,
                \Modules\TitanLeads\Console\Commands\RunTelegramCampaignCommand::class,
                \Modules\TitanLeads\Console\Commands\RunWhatsappCampaignCommand::class,
            ]);
        }
    }
}
