<?php

namespace Modules\Payroll\Contracts\Services;

use Modules\Payroll\Support\DTOs\PayslipDeliveryResult;
use Modules\Payroll\Support\DTOs\PayslipDocument;

interface PayslipDeliveryAuditServiceContract
{
    public function recordAttempt(PayslipDocument $document, array $employee, array $options = []): string;
    public function recordResult(string $deliveryId, PayslipDeliveryResult $result, array $meta = []): void;
    public function acknowledge(string $deliveryId, int $userId, array $meta = []): array;
}
