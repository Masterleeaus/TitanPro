<?php

namespace Modules\Payroll\Services\Exporters;

use Illuminate\Support\Collection;
use Modules\Payroll\Entities\PayrollRun;
use Modules\Payroll\Entities\SalarySlip;

class PayrollBankFileExporter
{
    public function toCsv(PayrollRun $run): string
    {
        $rows = collect([['employee_id', 'employee_name', 'amount', 'currency', 'reference']]);
        $this->slipsForRun($run)->each(function (SalarySlip $slip) use ($rows, $run) {
            $rows->push([
                $slip->user_id,
                optional($slip->user)->name,
                number_format((float) $slip->net_salary, 2, '.', ''),
                $slip->currency_id ?: '',
                $run->run_number ?: ('PAYRUN-'.$run->id),
            ]);
        });

        return $rows->map(fn (array $row) => implode(',', array_map([$this, 'escape'], $row)))->implode("\n")."\n";
    }

    private function slipsForRun(PayrollRun $run): Collection
    {
        return SalarySlip::query()
            ->with('user')
            ->where('company_id', $run->company_id)
            ->whereDate('salary_from', $run->period_start)
            ->whereDate('salary_to', $run->period_end)
            ->get();
    }

    private function escape(mixed $value): string
    {
        $value = (string) $value;
        if (str_contains($value, ',') || str_contains($value, '"')) {
            return '"'.str_replace('"', '""', $value).'"';
        }

        return $value;
    }
}
