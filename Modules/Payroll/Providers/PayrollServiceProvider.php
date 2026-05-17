<?php

namespace Modules\Payroll\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Payroll\Services\Domain\CleanerShiftReconciliationService;
use Modules\Payroll\Services\Reporting\PayrollSummaryService;
use Modules\Payroll\Services\Security\EmployeePayslipAccessTokenService;
use Modules\Payroll\Services\Domain\PayrollRunGuardService;
use Modules\Payroll\Contracts\Services\CleanerShiftReconciliationServiceContract;
use Modules\Payroll\Contracts\Services\PayrollSummaryServiceContract;
use Modules\Payroll\Contracts\Services\EmployeePayslipAccessTokenServiceContract;
use Modules\Payroll\Contracts\Services\PayrollRunGuardServiceContract;
use Modules\Payroll\Console\Commands\RetryFailedPayslipDeliveriesCommand;
use Modules\Payroll\Console\Commands\PayrollMvpDiagnosticsCommand;
use Modules\Payroll\Console\ActivateModuleCommand;
use Modules\Payroll\Console\Commands\PayrollDiagnosticsCommand;
use Modules\Payroll\Actions\Automation\RunPayrollAction;
use Modules\Payroll\Contracts\Actions\GeneratePayrollContract;
use Modules\Payroll\Contracts\Repositories\SalarySlipRepositoryContract;
use Modules\Payroll\Contracts\Services\PayrollCalculationServiceContract;
use Modules\Payroll\Contracts\Services\PayrollRunServiceContract;
use Modules\Payroll\Contracts\Services\PayslipDeliveryServiceContract;
use Modules\Payroll\Contracts\Services\PayslipAccessLinkServiceContract;
use Modules\Payroll\Contracts\Services\PayslipDeliveryAuditServiceContract;
use Modules\Payroll\Contracts\Services\PayslipPreferenceServiceContract;
use Modules\Payroll\Contracts\Workflows\PayrollWorkflowContract;
use Modules\Payroll\Repositories\Eloquent\SalarySlipRepository;
use Modules\Payroll\Services\Core\PayrollCalculationService;
use Modules\Payroll\Services\Core\PayrollRunService;
use Modules\Payroll\Services\Notifications\PayslipDeliveryService;
use Modules\Payroll\Services\Notifications\PayslipDeliveryAuditService;
use Modules\Payroll\Services\Notifications\PayslipPreferenceService;
use Modules\Payroll\Services\Security\PayslipAccessLinkService;
use Modules\Payroll\Workflows\Definitions\PayrollApprovalWorkflow;

class PayrollServiceProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->commands([
            ActivateModuleCommand::class,
            PayrollDiagnosticsCommand::class,
            PayrollMvpDiagnosticsCommand::class,
            RetryFailedPayslipDeliveriesCommand::class,
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);

        $this->app->bind(PayrollCalculationServiceContract::class, PayrollCalculationService::class);
        $this->app->bind(PayrollRunServiceContract::class, PayrollRunService::class);
        $this->app->bind(PayslipDeliveryServiceContract::class, PayslipDeliveryService::class);
        $this->app->bind(PayslipDeliveryAuditServiceContract::class, PayslipDeliveryAuditService::class);
        $this->app->bind(PayslipPreferenceServiceContract::class, PayslipPreferenceService::class);
        $this->app->bind(PayslipAccessLinkServiceContract::class, PayslipAccessLinkService::class);
        $this->app->bind(SalarySlipRepositoryContract::class, SalarySlipRepository::class);
        $this->app->bind(GeneratePayrollContract::class, RunPayrollAction::class);
        $this->app->bind(PayrollWorkflowContract::class, PayrollApprovalWorkflow::class);
        $this->app->bind(CleanerShiftReconciliationServiceContract::class, CleanerShiftReconciliationService::class);
        $this->app->bind(PayrollSummaryServiceContract::class, PayrollSummaryService::class);
        $this->app->bind(EmployeePayslipAccessTokenServiceContract::class, EmployeePayslipAccessTokenService::class);
        $this->app->bind(PayrollRunGuardServiceContract::class, PayrollRunGuardService::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            __DIR__.'/../Config/config.php' => config_path('payroll.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../Config/config.php', 'payroll'
        );

        $this->mergeConfigFrom(
            module_path('payroll', 'Config/xss_ignore.php'),
            'payroll::xss_ignore'
        );

        $this->mergeConfigFrom(__DIR__.'/../Config/compliance.php', 'payroll.compliance');
        $this->mergeConfigFrom(__DIR__.'/../Config/tax.php', 'payroll.tax');
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/payroll');

        $sourcePath = __DIR__.'/../Resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
        ], 'views');

        $this->loadViewsFrom([$sourcePath], 'payroll');
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/payroll');

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, 'payroll');

        } else {
            $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'payroll');
        }
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (! app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(__DIR__.'/../Database/factories');
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            PayrollCalculationServiceContract::class,
            PayrollRunServiceContract::class,
            PayslipDeliveryServiceContract::class,
            SalarySlipRepositoryContract::class,
            GeneratePayrollContract::class,
            PayrollWorkflowContract::class,
            CleanerShiftReconciliationServiceContract::class,
            PayrollSummaryServiceContract::class,
            EmployeePayslipAccessTokenServiceContract::class,
            PayrollRunGuardServiceContract::class,
        ];
    }
}
