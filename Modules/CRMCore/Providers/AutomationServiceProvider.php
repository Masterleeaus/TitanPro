<?php

namespace Modules\CRMCore\Providers;

use App\Platform\Automation\AutomationRegistry;
use Illuminate\Support\ServiceProvider;
use Modules\CRMCore\Automation\Handlers\CreateProjectForWonDealHandler;

class AutomationServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /** @var AutomationRegistry $registry */
        $registry = $this->app->make(AutomationRegistry::class);

        $registry->register([
            'id'          => 'crm.deal_won_create_project',
            'trigger'     => 'crmcore.deal.won',
            'handler'     => CreateProjectForWonDealHandler::class,
            'retries'     => 3,
            'retry_after' => 60,
        ]);

        $registry->register([
            'id'       => 'crm.stale_deal_check',
            'trigger'  => 'crm.stale_deal_check',
            'handler'  => null,
            'schedule' => 'daily',
        ]);
    }
}
