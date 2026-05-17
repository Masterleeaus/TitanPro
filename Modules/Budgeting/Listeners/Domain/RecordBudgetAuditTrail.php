<?php

declare(strict_types=1);

namespace Modules\Budgeting\Listeners\Domain;

class RecordBudgetAuditTrail
{
    public function handle(object $event): void
    {
        $modelClass = get_class($event);
        $eventName = class_basename($modelClass);

        \Illuminate\Support\Facades\Log::channel('stack')->info("BudgetAudit: {$eventName}", [
            'event' => $eventName,
            'payload' => method_exists($event, 'model') ? $event->model->toArray() : [],
        ]);
    }
}
