<?php

namespace Modules\Payroll\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResendFailedPayslipsCommand extends Command
{
    protected $signature = 'payroll:payslips:resend-failed {--limit=50}';
    protected $description = 'List failed payslip deliveries for operational resend handling.';

    public function handle(): int
    {
        $rows = DB::table('payroll_payslip_deliveries')
            ->whereIn('status', ['failed', 'queued'])
            ->oldest('created_at')
            ->limit((int) $this->option('limit'))
            ->get(['uuid', 'user_id', 'salary_slip_id', 'recipient', 'status', 'error']);

        $this->table(['uuid', 'user_id', 'salary_slip_id', 'recipient', 'status', 'error'], $rows->map(fn ($row) => (array) $row));

        return self::SUCCESS;
    }
}
