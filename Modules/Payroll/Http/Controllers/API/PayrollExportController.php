<?php

namespace Modules\Payroll\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Modules\Payroll\Contracts\Repositories\PayrollRunRepositoryContract;
use Modules\Payroll\Services\Exporters\PayrollBankFileExporter;

class PayrollExportController extends Controller
{
    public function bankFile(int $run, PayrollRunRepositoryContract $runs, PayrollBankFileExporter $exporter): Response
    {
        $payrollRun = $runs->findForCompany((int) company()->id, $run);
        abort_if(! $payrollRun, 404, 'Payroll run not found.');

        return response($exporter->toCsv($payrollRun), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="payroll-run-'.$payrollRun->id.'-bank.csv"',
        ]);
    }
}
