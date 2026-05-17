<?php

namespace Modules\Payroll\Actions\Import;

use Illuminate\Support\Collection;
use Modules\Payroll\Services\Importers\PayrollAdjustmentImporter;

class ImportPayrollAdjustmentsAction
{
    public function __construct(private readonly PayrollAdjustmentImporter $importer) {}

    public function execute(string $csv): Collection
    {
        return $this->importer->parseCsv($csv);
    }
}
