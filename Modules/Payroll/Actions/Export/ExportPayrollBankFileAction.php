<?php

namespace Modules\Payroll\Actions\Export;

use Modules\Payroll\Entities\PayrollRun;
use Modules\Payroll\Services\Exporters\PayrollBankFileExporter;

class ExportPayrollBankFileAction
{
    public function __construct(private readonly PayrollBankFileExporter $exporter) {}

    public function execute(PayrollRun $run): string
    {
        return $this->exporter->toCsv($run);
    }
}
