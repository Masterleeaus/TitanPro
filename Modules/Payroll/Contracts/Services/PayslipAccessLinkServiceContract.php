<?php

namespace Modules\Payroll\Contracts\Services;

use Modules\Payroll\Support\DTOs\PayslipDocument;

interface PayslipAccessLinkServiceContract
{
    public function temporaryLink(PayslipDocument $document, array $employee = [], array $options = []): ?string;
    public function verify(string $token): array;
}
