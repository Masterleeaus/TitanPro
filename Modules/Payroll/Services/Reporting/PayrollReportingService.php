<?php

namespace Modules\Payroll\Services\Reporting;

use Modules\Payroll\Contracts\Services\PayrollReportingServiceContract;

class PayrollReportingService implements PayrollReportingServiceContract
{
    public function summary(array $rows): array
    {
        $gross = array_sum(array_map(fn ($row) => (float) ($row['gross_pay'] ?? $row['gross'] ?? 0), $rows));
        $net = array_sum(array_map(fn ($row) => (float) ($row['net_pay'] ?? $row['net'] ?? 0), $rows));
        return ['count' => count($rows), 'gross_total' => round($gross, 2), 'net_total' => round($net, 2), 'deduction_total' => round($gross - $net, 2)];
    }

    public function toCsv(array $rows): string
    {
        if ($rows === []) {
            return '';
        }
        $headers = array_keys($rows[0]);
        $fh = fopen('php://temp', 'r+');
        fputcsv($fh, $headers);
        foreach ($rows as $row) {
            fputcsv($fh, array_map(fn ($header) => $row[$header] ?? null, $headers));
        }
        rewind($fh);
        return stream_get_contents($fh) ?: '';
    }
}
