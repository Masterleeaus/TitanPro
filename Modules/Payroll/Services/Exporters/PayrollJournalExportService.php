<?php

namespace Modules\Payroll\Services\Exporters;

use Modules\Payroll\Contracts\Services\PayrollJournalExportServiceContract;

class PayrollJournalExportService implements PayrollJournalExportServiceContract
{
    public function build(array $payrollRun, array $options = []): array
    {
        $currency = $options['currency'] ?? $payrollRun['currency'] ?? config('app.currency', 'USD');
        $gross = (float) ($payrollRun['gross_total'] ?? $payrollRun['gross'] ?? 0);
        $net = (float) ($payrollRun['net_total'] ?? $payrollRun['net'] ?? 0);
        $tax = (float) ($payrollRun['tax_total'] ?? max(0, $gross - $net));
        $payrollClearing = $options['payroll_clearing_account'] ?? 'Payroll Clearing';

        return [
            'reference' => $options['reference'] ?? ('PAYROLL-'.$payrollRun['period_from'].'-'.$payrollRun['period_to']),
            'currency' => $currency,
            'lines' => array_values(array_filter([
                ['account' => $options['wages_account'] ?? 'Wages Expense', 'debit' => round($gross, 2), 'credit' => 0.0, 'memo' => 'Gross payroll'],
                $tax > 0 ? ['account' => $options['tax_payable_account'] ?? 'Payroll Tax Payable', 'debit' => 0.0, 'credit' => round($tax, 2), 'memo' => 'Estimated payroll taxes/deductions'] : null,
                ['account' => $payrollClearing, 'debit' => 0.0, 'credit' => round($net, 2), 'memo' => 'Net payroll payable'],
            ])),
            'metadata' => ['source' => 'payroll', 'run' => $payrollRun],
        ];
    }

    public function toCsv(array $journal): string
    {
        $rows = [['reference', 'account', 'debit', 'credit', 'currency', 'memo']];
        foreach ($journal['lines'] ?? [] as $line) {
            $rows[] = [
                $journal['reference'] ?? '',
                $line['account'] ?? '',
                number_format((float) ($line['debit'] ?? 0), 2, '.', ''),
                number_format((float) ($line['credit'] ?? 0), 2, '.', ''),
                $journal['currency'] ?? '',
                $line['memo'] ?? '',
            ];
        }

        return collect($rows)->map(fn ($row) => implode(',', array_map(fn ($value) => '"'.str_replace('"', '""', (string) $value).'"', $row)))->implode("\n");
    }
}
