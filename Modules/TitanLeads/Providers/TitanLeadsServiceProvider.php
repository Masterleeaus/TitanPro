<?php

namespace Modules\TitanLeads\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\TitanLeads\Models\MarketingCampaign;
use Modules\TitanLeads\Models\Whatsapp\ContactList;
use Modules\TitanLeads\Models\Whatsapp\Segment;
use Modules\TitanLeads\Policies\ContactListPolicy;
use Modules\TitanLeads\Policies\MarketingCampaignPolicy;
use Modules\TitanLeads\Policies\SegmentPolicy;
use Modules\TitanLeads\Services\InboundIngestService;
use Modules\TitanLeads\Services\InboxService;
use Modules\TitanLeads\Services\Outbox\OutboxService;

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

        Gate::policy(MarketingCampaign::class, MarketingCampaignPolicy::class);
        Gate::policy(ContactList::class, ContactListPolicy::class);
        Gate::policy(Segment::class, SegmentPolicy::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                \Modules\TitanLeads\Console\Commands\RunInvoiceFollowupsCommand::class,
                \Modules\TitanLeads\Console\Commands\RunTelegramCampaignCommand::class,
                \Modules\TitanLeads\Console\Commands\RunWhatsappCampaignCommand::class,
            ]);
        }
    }
}
