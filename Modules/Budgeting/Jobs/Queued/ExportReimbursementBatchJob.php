<?php

declare(strict_types=1);

namespace Modules\Budgeting\Jobs\Queued;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Budgeting\Models\ReimbursementBatch;

class ExportReimbursementBatchJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly ReimbursementBatch $batch,
        public readonly string $format = 'csv',
    ) {}

    public function handle(): void
    {
        // Generate export file and store or notify user
    }
}
