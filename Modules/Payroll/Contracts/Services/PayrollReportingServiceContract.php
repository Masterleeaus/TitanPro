<?php

namespace Modules\Payroll\Contracts\Services;

interface PayrollReportingServiceContract
{
    public function summary(array $rows): array;
    public function toCsv(array $rows): string;
}
