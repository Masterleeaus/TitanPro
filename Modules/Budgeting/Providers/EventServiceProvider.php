<?php

declare(strict_types=1);

namespace Modules\Budgeting\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Budgeting\Events\Domain\BudgetActualPosted;
use Modules\Budgeting\Events\Domain\ExpenseApproved;
use Modules\Budgeting\Events\Domain\ExpenseSubmitted;
use Modules\Budgeting\Events\Domain\ForecastScenarioGenerated;
use Modules\Budgeting\Events\Domain\ReceiptExtracted;
use Modules\Budgeting\Events\Domain\ReimbursementBatchPaid;
use Modules\Budgeting\Events\Domain\VarianceDetected;
use Modules\Budgeting\Listeners\Domain\NotifyApprovers;
use Modules\Budgeting\Listeners\Domain\QueueReceiptOcr;
use Modules\Budgeting\Listeners\Domain\RecordBudgetAuditTrail;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ExpenseSubmitted::class => [
            NotifyApprovers::class,
            RecordBudgetAuditTrail::class,
        ],
        ExpenseApproved::class => [
            RecordBudgetAuditTrail::class,
        ],
        ReceiptExtracted::class => [
            QueueReceiptOcr::class,
            RecordBudgetAuditTrail::class,
        ],
        BudgetActualPosted::class => [
            RecordBudgetAuditTrail::class,
        ],
        VarianceDetected::class => [
            RecordBudgetAuditTrail::class,
        ],
        ReimbursementBatchPaid::class => [
            RecordBudgetAuditTrail::class,
        ],
        ForecastScenarioGenerated::class => [
            RecordBudgetAuditTrail::class,
        ],
    ];

    public function boot(): void
    {
        parent::boot();
    }
}
