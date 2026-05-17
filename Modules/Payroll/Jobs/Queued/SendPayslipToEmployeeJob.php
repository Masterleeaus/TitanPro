<?php

namespace Modules\Payroll\Jobs\Queued;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Payroll\Contracts\Services\PayslipDeliveryServiceContract;
use Modules\Payroll\Support\DTOs\PayslipDocument;

class SendPayslipToEmployeeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $document, public array $employee = [], public array $options = []) {}

    public function handle(PayslipDeliveryServiceContract $delivery): void
    {
        $document = new PayslipDocument(
            userId: (int) $this->document['user_id'],
            periodFrom: (string) $this->document['period_from'],
            periodTo: (string) $this->document['period_to'],
            html: (string) ($this->document['html'] ?? ''),
            payload: $this->document['payload'] ?? [],
            storagePath: $this->document['storage_path'] ?? null,
        );

        $delivery->deliver($document, $this->employee, $this->options);
    }
}
