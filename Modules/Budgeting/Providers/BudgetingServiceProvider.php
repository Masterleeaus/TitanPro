<?php

declare(strict_types=1);

namespace Modules\Budgeting\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Budgeting\Contracts\Services\AnalyticsFeedsServiceContract;
use Modules\Budgeting\Contracts\Services\ApprovalThresholdsServiceContract;
use Modules\Budgeting\Contracts\Services\BudgetActualsServiceContract;
use Modules\Budgeting\Contracts\Services\ExpensesServiceContract;
use Modules\Budgeting\Contracts\Services\ForecastingServiceContract;
use Modules\Budgeting\Contracts\Services\ReceiptsServiceContract;
use Modules\Budgeting\Contracts\Services\ReimbursementsServiceContract;
use Modules\Budgeting\Contracts\Services\VarianceServiceContract;
use Modules\Budgeting\Services\ApprovalThresholds\ApprovalThresholdsService;
use Modules\Budgeting\Services\BudgetActuals\BudgetActualsService;
use Modules\Budgeting\Services\Expenses\ExpensesService;
use Modules\Budgeting\Services\Forecasting\ForecastingService;
use Modules\Budgeting\Services\Receipts\ReceiptsService;
use Modules\Budgeting\Services\Reimbursements\ReimbursementsService;
use Modules\Budgeting\Services\Variance\VarianceService;

class BudgetingServiceProvider extends ServiceProvider
{
    protected string $moduleName = 'Budgeting';

    protected string $moduleNameLower = 'budgeting';

    public function boot(): void
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
    }

    public function register(): void
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(ExpensesServiceContract::class, ExpensesService::class);
        $this->app->bind(ReceiptsServiceContract::class, ReceiptsService::class);
        $this->app->bind(ReimbursementsServiceContract::class, ReimbursementsService::class);
        $this->app->bind(BudgetActualsServiceContract::class, BudgetActualsService::class);
        $this->app->bind(VarianceServiceContract::class, VarianceService::class);
        $this->app->bind(ForecastingServiceContract::class, ForecastingService::class);
        $this->app->bind(ApprovalThresholdsServiceContract::class, ApprovalThresholdsService::class);
    }

    protected function registerConfig(): void
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/budgeting.php') => config_path('budgeting.php'),
            module_path($this->moduleName, 'Config/expenses.php') => config_path('expenses.php'),
            module_path($this->moduleName, 'Config/actuals.php') => config_path('actuals.php'),
        ], 'config');

        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/budgeting.php'), 'budgeting');
        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/expenses.php'), 'expenses');
        $this->mergeConfigFrom(module_path($this->moduleName, 'Config/actuals.php'), 'actuals');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(module_path($this->moduleName, 'Resources/views'), $this->moduleNameLower);
    }

    protected function registerTranslations(): void
    {
        $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
    }
}
