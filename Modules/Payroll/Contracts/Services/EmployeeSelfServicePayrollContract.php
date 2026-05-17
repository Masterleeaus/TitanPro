<?php

namespace Modules\Payroll\Contracts\Services;

interface EmployeeSelfServicePayrollContract
{
    public function payslipsFor(int $userId, array $filters = []): array;

    public function bankDetailsStatus(int $userId): array;

    public function updateBankDetails(int $userId, array $payload): array;

    public function taxDeclarationStatus(int $userId): array;
}
