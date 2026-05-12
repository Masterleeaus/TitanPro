<?php

namespace Modules\CRMCore\Providers;

use App\Platform\Automation\AutomationRegistry;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Modules\CRMCore\Automation\Handlers\CreateProjectForWonDealHandler;
use Modules\CRMCore\Console\Commands\ModulesPermissionsSyncCommand;
use Modules\CRMCore\Interfaces\PipelineMetricProvider;
use Modules\CRMCore\Models\CRMCoreActivityLog;
use Modules\CRMCore\Models\Deal;
use Modules\CRMCore\Models\Lead;
use Modules\CRMCore\Policies\CRMCoreActivityLogPolicy;
use Modules\CRMCore\Policies\DealPolicy;
use Modules\CRMCore\Policies\LeadPolicy;
use Modules\CRMCore\Repositories\PipelineRepository;
use Modules\CRMCore\Services\BillingEntityDetector;
use Modules\CRMCore\Workflows\Definitions\DealToProjectWorkflow;
use Nwidart\Modules\Facades\Module;

class CRMCoreServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../Config/config.php', 'crmcore');
        $this->mergeConfigFrom(__DIR__ . '/../Config/tenancy.php', 'crmcore.tenancy');
        $this->mergeConfigFrom(__DIR__ . '/../Config/billing.php', 'crmcore.billing');
        $this->mergeConfigFrom(__DIR__ . '/../Config/search.php', 'crmcore.search');

        // BillingEntityDetector resolves CRM entities that can be linked to billing surfaces.
        $this->app->singleton(BillingEntityDetector::class);
        // PipelineMetricProvider exposes tenant-scoped lead/deal metrics for dashboards and AI tools.
        $this->app->bind(PipelineMetricProvider::class, PipelineRepository::class);

        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(FilamentServiceProvider::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'crmcore');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'crmcore');

        if ($this->app->runningInConsole()) {
            $this->commands([
                ModulesPermissionsSyncCommand::class,
            ]);
        }

        Gate::policy(Lead::class, LeadPolicy::class);
        Gate::policy(Deal::class, DealPolicy::class);
        Gate::policy(CRMCoreActivityLog::class, CRMCoreActivityLogPolicy::class);

        $this->registerAutomation();
        $this->registerWorkflows();
        $this->registerNavigationManifest();
    }

    private function registerAutomation(): void
    {
        if (! $this->app->bound(AutomationRegistry::class)) {
            return;
        }

        /** @var AutomationRegistry $registry */
        $registry = $this->app->make(AutomationRegistry::class);

        $registry->register([
            'id' => 'crm.deal_won_create_project',
            'trigger' => 'crmcore.deal.won',
            'handler' => CreateProjectForWonDealHandler::class,
            'retries' => 3,
            'retry_after' => 60,
        ]);

        $registry->register([
            'id' => 'crm.stale_deal_check',
            'trigger' => 'crm.stale_deal_check',
            'handler' => null,
            'schedule' => 'daily',
        ]);
    }

    private function registerWorkflows(): void
    {
        if (! class_exists(\Workflow\WorkflowRegistry::class)) {
            return;
        }

        foreach ($this->workflowClasses() as $workflowClass) {
            if (class_exists($workflowClass)) {
                \Workflow\WorkflowRegistry::register($workflowClass);
            }
        }
    }

    /**
     * @return array<int, class-string>
     */
    private function workflowClasses(): array
    {
        $manifestPath = __DIR__ . '/../manifests/workflows.manifest.json';
        if (! file_exists($manifestPath)) {
            return [DealToProjectWorkflow::class];
        }

        $raw = file_get_contents($manifestPath);
        if ($raw === false) {
            return [DealToProjectWorkflow::class];
        }

        $manifest = json_decode($raw, true);
        if (! is_array($manifest)) {
            return [DealToProjectWorkflow::class];
        }

        $workflows = $manifest['workflows'] ?? [];
        if (! is_array($workflows) || $workflows === []) {
            return [DealToProjectWorkflow::class];
        }

        return array_values(array_filter(array_map(function ($workflow): ?string {
            if (! is_string($workflow) || $workflow === '') {
                return null;
            }

            if (str_contains($workflow, '\\')) {
                return $workflow;
            }

            return 'Modules\\CRMCore\\Workflows\\Definitions\\' . $workflow;
        }, $workflows)));
    }

    private function registerNavigationManifest(): void
    {
        if ($this->moduleIsDisabled()) {
            return;
        }

        $manifestPath = __DIR__ . '/../manifests/navigation.manifest.json';
        if (! file_exists($manifestPath)) {
            return;
        }

        $raw = file_get_contents($manifestPath);
        if ($raw === false) {
            return;
        }

        $decoded = json_decode($raw, true);
        $decoded = is_array($decoded) ? $decoded : [];
        $groups = is_array($decoded['groups'] ?? null) ? $decoded['groups'] : [];

        if ($groups === []) {
            return;
        }

        $registry = config('titan.navigation.manifests', []);
        if (! is_array($registry)) {
            $registry = [];
        }

        $registry['crmcore'] = $groups;

        config(['titan.navigation.manifests' => $registry]);
    }

    private function moduleIsDisabled(): bool
    {
        if (! class_exists(Module::class) || ! app()->bound('modules')) {
            return false;
        }

        if (! Module::has('CRMCore')) {
            return false;
        }

        return ! Module::isEnabled('CRMCore');
    }
}
