<?php

namespace Modules\Payroll\Jobs\Queued;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Payroll\Contracts\Services\PayrollRunServiceContract;
use Modules\Payroll\Events\Domain\PayrollRunCompleted;

class GeneratePayrollRunJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct(public readonly int $companyId, public readonly string $from, public readonly string $to, public readonly ?array $userIds = null, public readonly array $options = []) {}
    public function handle(PayrollRunServiceContract $service): void
    {
        $result = $service->run($this->companyId, Carbon::parse($this->from), Carbon::parse($this->to), $this->userIds, $this->options);
        event(new PayrollRunCompleted($result));
    }
}
