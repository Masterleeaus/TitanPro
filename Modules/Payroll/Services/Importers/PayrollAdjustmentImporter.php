<?php

namespace Modules\Payroll\Services\Importers;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PayrollAdjustmentImporter
{
    public function parseCsv(string $contents): Collection
    {
        $lines = collect(preg_split('/\r\n|\r|\n/', trim($contents)))->filter();
        $header = str_getcsv((string) $lines->shift());

        return $lines->map(function (string $line) use ($header) {
            $row = array_combine($header, str_getcsv($line));

            return [
                'employee_id' => (int) ($row['employee_id'] ?? 0),
                'code' => Str::upper(trim((string) ($row['code'] ?? 'ADJUSTMENT'))),
                'amount' => round((float) ($row['amount'] ?? 0), 2),
                'description' => trim((string) ($row['description'] ?? '')),
            ];
        })->values();
    }
}
