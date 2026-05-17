<?php

namespace Modules\Payroll\Events\Domain;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Payroll\Support\DTOs\PayrollRunResult;

class PayrollRunCompleted
{
    use Dispatchable, SerializesModels;
    public function __construct(public readonly PayrollRunResult $result) {}
}
