<?php

namespace Modules\Payroll\Contracts\Services;

interface PayrollJournalExportServiceContract
{
    public function build(array $payrollRun, array $options = []): array;
    public function toCsv(array $journal): string;
}
