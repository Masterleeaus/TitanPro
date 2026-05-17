<?php

namespace Modules\Payroll\Contracts\Services;

use Modules\Payroll\Support\DTOs\PayslipDeliveryResult;
use Modules\Payroll\Support\DTOs\PayslipDocument;

interface PayslipDeliveryServiceContract
{
    public function deliver(PayslipDocument $document, array $employee = [], array $options = []): PayslipDeliveryResult;
}
