<?php

namespace App\Providers;

use App\Console\Commands\AutomationsRequeueCommand;
use App\Console\Commands\AutomationsStatusCommand;
use App\Platform\Automation\AutomationRegistry;
use App\Platform\Automation\HandlerExecutor;
use App\Platform\Automation\PipelineRunner;
use App\Platform\Automation\SchedulerBridge;
use App\Platform\Automation\TriggerDispatcher;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

/**
 * AutomationEngineServiceProvider
 *
 * Registers the core automation engine singletons and wires scheduled automations
 * into the Laravel task scheduler after all module providers have run.
 *
 * Module AutomationServiceProviders should use the bound AutomationRegistry to
 * register their automation definitions in their boot() method:
 *
 *   app(AutomationRegistry::class)->register([
 *       'id'      => 'crm.deal_won',
 *       'trigger' => 'crmcore.deal.won',
 *       'handler' => CreateProjectForWonDealHandler::class,
 *       'retries' => 3,
 *   ]);
 */
class AutomationEngineServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Core registry — singleton so every module shares the same instance.
        $this->app->singleton(AutomationRegistry::class);

        // Engine singletons — resolved lazily.
        $this->app->singleton(TriggerDispatcher::class);
        $this->app->singleton(HandlerExecutor::class);
        $this->app->singleton(PipelineRunner::class);
        $this->app->singleton(SchedulerBridge::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                AutomationsStatusCommand::class,
                AutomationsRequeueCommand::class,
            ]);
        }

        // Bind scheduled automations into the Laravel task scheduler.
        // We do this after all providers have booted so every module has had
        // the chance to register its automations with the registry.
        $this->callAfterResolving(Schedule::class, function (Schedule $schedule) {
            /** @var SchedulerBridge $bridge */
            $bridge = $this->app->make(SchedulerBridge::class);
            $bridge->bind($schedule);
        });
    }
}
