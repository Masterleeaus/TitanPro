<?php

namespace Modules\Payroll\Services\Domain;

use Illuminate\Support\Facades\DB;
use Modules\Payroll\Contracts\Services\PayrollRunGuardServiceContract;
use Modules\Payroll\Support\DTOs\PayrollRunGuardResult;

class PayrollRunGuardService implements PayrollRunGuardServiceContract
{
    public function inspect(array $payload): PayrollRunGuardResult
    {
        $errors = [];
        $warnings = [];
        $companyId = $payload['company_id'] ?? (function_exists('company') ? company()->id : null);
        $periodFrom = $payload['period_from'] ?? $payload['start_date'] ?? null;
        $periodTo = $payload['period_to'] ?? $payload['end_date'] ?? null;

        if (! $companyId) {
            $errors[] = 'Missing company_id for payroll run guard.';
        }
        if (! $periodFrom || ! $periodTo) {
            $errors[] = 'Missing period_from/period_to for payroll run guard.';
        }
        if ($periodFrom && $periodTo && $periodFrom > $periodTo) {
            $errors[] = 'Payroll period start date must be before end date.';
        }

        $duplicateCount = 0;
        if ($companyId && $periodFrom && $periodTo && DB::getSchemaBuilder()->hasTable('payroll_runs')) {
            $duplicateCount = DB::table('payroll_runs')
                ->where('company_id', $companyId)
                ->where('period_from', $periodFrom)
                ->where('period_to', $periodTo)
                ->whereIn('status', ['draft', 'pending_approval', 'approved', 'finalized', 'paid'])
                ->count();
            if ($duplicateCount > 0 && config('payroll.features.prevent_duplicate_runs', true)) {
                $errors[] = 'A payroll run already exists for this company and period.';
            }
        }

        if ($companyId && $periodFrom && $periodTo && DB::getSchemaBuilder()->hasTable('payroll_period_locks')) {
            $locked = DB::table('payroll_period_locks')
                ->where('company_id', $companyId)
                ->where('period_from', '<=', $periodTo)
                ->where('period_to', '>=', $periodFrom)
                ->whereNull('unlocked_at')
                ->exists();
            if ($locked) {
                $errors[] = 'Payroll period overlaps an active lock.';
            }
        }

        if (($payload['employee_count'] ?? 1) <= 0) {
            $warnings[] = 'No employees were supplied for this payroll run.';
        }

        return new PayrollRunGuardResult(empty($errors), $errors, $warnings, [
            'duplicate_count' => $duplicateCount,
            'company_id' => $companyId,
            'period_from' => $periodFrom,
            'period_to' => $periodTo,
        ]);
    }

    public function assertCanFinalize(int|string $runId, array $context = []): PayrollRunGuardResult
    {
        if (! DB::getSchemaBuilder()->hasTable('payroll_runs')) {
            return new PayrollRunGuardResult(false, ['payroll_runs table is missing.']);
        }

        $run = DB::table('payroll_runs')->where('id', $runId)->first();
        if (! $run) {
            return new PayrollRunGuardResult(false, ['Payroll run not found.']);
        }

        $errors = [];
        $warnings = [];
        if (in_array($run->status ?? null, ['finalized', 'paid'], true)) {
            $errors[] = 'Payroll run is already finalized or paid.';
        }
        if (! in_array($run->status ?? null, ['approved', 'pending_approval'], true)) {
            $warnings[] = 'Payroll run is not currently approved.';
        }

        return new PayrollRunGuardResult(empty($errors), $errors, $warnings, ['run_id' => $runId, 'status' => $run->status ?? null]);
    }
}
