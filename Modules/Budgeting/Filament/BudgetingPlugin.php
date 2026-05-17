<?php

declare(strict_types=1);

namespace Modules\Budgeting\Filament;

use Filament\Contracts\Plugin;
use Filament\Panel;
use Modules\Budgeting\Filament\Resources\ApprovalThresholdResource;
use Modules\Budgeting\Filament\Resources\BudgetActualResource;
use Modules\Budgeting\Filament\Resources\ExpenseResource;
use Modules\Budgeting\Filament\Resources\ForecastScenarioResource;
use Modules\Budgeting\Filament\Resources\ReceiptResource;
use Modules\Budgeting\Filament\Resources\ReimbursementBatchResource;
use Modules\Budgeting\Filament\Resources\VarianceResource;
use Modules\Budgeting\Filament\Widgets\BudgetVsActualWidget;
use Modules\Budgeting\Filament\Widgets\PendingApprovalsWidget;
use Modules\Budgeting\Filament\Widgets\SpendTrendWidget;
use Modules\Budgeting\Filament\Widgets\VarianceAlertWidget;

class BudgetingPlugin implements Plugin
{
    public function getId(): string
    {
        return 'budgeting-module';
    }

    public function register(Panel $panel): void
    {
        $panel
            ->resources([
                ExpenseResource::class,
                ReceiptResource::class,
                ReimbursementBatchResource::class,
                BudgetActualResource::class,
                VarianceResource::class,
                ForecastScenarioResource::class,
                ApprovalThresholdResource::class,
            ])
            ->widgets([
                BudgetVsActualWidget::class,
                SpendTrendWidget::class,
                VarianceAlertWidget::class,
                PendingApprovalsWidget::class,
            ]);
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }
}
