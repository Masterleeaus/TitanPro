<?php

namespace Modules\Payroll\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Payroll\Contracts\Services\PayrollJournalExportServiceContract;

class PayrollJournalExportController extends Controller
{
    public function __invoke(Request $request, PayrollJournalExportServiceContract $journals)
    {
        $data = $request->validate([
            'payroll_run' => ['required', 'array'],
            'options' => ['sometimes', 'array'],
            'format' => ['sometimes', 'in:json,csv'],
        ]);

        $journal = $journals->build($data['payroll_run'], $data['options'] ?? []);

        if (($data['format'] ?? 'json') === 'csv') {
            return response($journals->toCsv($journal), 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="payroll-journal.csv"',
            ]);
        }

        return response()->json($journal);
    }
}
