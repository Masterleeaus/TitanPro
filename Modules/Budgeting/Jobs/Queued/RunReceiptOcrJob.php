<?php

declare(strict_types=1);

namespace Modules\Budgeting\Jobs\Queued;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Budgeting\Models\Receipt;

class RunReceiptOcrJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public int $tries = 3;

    public int $timeout = 120;

    public function __construct(public readonly Receipt $receipt) {}

    public function handle(): void
    {
        $this->receipt->update(['ocr_status' => 'processing']);

        // OCR processing logic — integrate with external OCR provider
        // On success: update ocr_status='done', ocr_data, extracted_amount, extracted_date, extracted_vendor
        // On failure: update ocr_status='failed'

        $this->receipt->update(['ocr_status' => 'done']);
    }
}
