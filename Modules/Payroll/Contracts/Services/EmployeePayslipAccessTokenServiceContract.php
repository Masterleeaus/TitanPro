<?php

namespace Modules\Payroll\Contracts\Services;

interface EmployeePayslipAccessTokenServiceContract
{
    public function issue(int $employeeId, int|string $payslipId, array $claims = []): array;
    public function validate(string $token, int|string $payslipId): array;
}
