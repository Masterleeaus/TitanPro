<?php

declare(strict_types=1);

namespace Modules\Budgeting\Listeners\Domain;

use Modules\Budgeting\Events\Domain\ReceiptExtracted;
use Modules\Budgeting\Jobs\Queued\RunReceiptOcrJob;

class QueueReceiptOcr
{
    public function handle(ReceiptExtracted $event): void
    {
        if (config('expenses.ocr_enabled', true)) {
            RunReceiptOcrJob::dispatch($event->model);
        }
    }
}
