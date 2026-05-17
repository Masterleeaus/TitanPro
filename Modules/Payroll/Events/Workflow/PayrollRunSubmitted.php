<?php

namespace Modules\Payroll\Events\Workflow;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Payroll\Entities\PayrollRun;

class PayrollRunSubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly PayrollRun $run) {}
}
