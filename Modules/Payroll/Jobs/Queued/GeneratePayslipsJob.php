<?php

namespace Modules\Payroll\Jobs\Queued;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Payroll\Actions\Reporting\GeneratePayslipAction;

class GeneratePayslipsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public array $results, public array $company = [], public array $options = []) {}

    public function handle(GeneratePayslipAction $action): void
    {
        foreach ($this->results as $row) {
            $action->execute($row, $row['employee'] ?? [], $this->company, $this->options);
        }
    }
}
