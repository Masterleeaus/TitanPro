<?php

namespace Modules\Payroll\Services\Core;

use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Modules\Payroll\Contracts\Repositories\SalarySlipRepositoryContract;
use Modules\Payroll\Contracts\Services\PayrollCalculationServiceContract;
use Modules\Payroll\Contracts\Services\PayrollRunServiceContract;
use Modules\Payroll\Entities\EmployeeMonthlySalary;
use Modules\Payroll\Support\DTOs\PayrollCalculationInput;
use Modules\Payroll\Support\DTOs\PayrollRunResult;
use Modules\Payroll\Support\Enums\PayrollRunStatus;

class PayrollRunService implements PayrollRunServiceContract
{
    public function __construct(private readonly PayrollCalculationServiceContract $calculator, private readonly SalarySlipRepositoryContract $slips) {}

    public function preview(int $companyId, CarbonInterface $from, CarbonInterface $to, ?array $userIds = null): Collection
    {
        return $this->employees($companyId, $userIds)->map(function ($user) use ($companyId, $from, $to) {
            $salary = EmployeeMonthlySalary::employeeNetSalary($user->id, $to->toDateString());
            return $this->calculator->calculate(new PayrollCalculationInput(companyId: $companyId, userId: $user->id, from: $from, to: $to, baseSalary: (float) ($salary['netSalary'] ?? 0)))->toArray();
        });
    }

    public function run(int $companyId, CarbonInterface $from, CarbonInterface $to, ?array $userIds = null, array $options = []): PayrollRunResult
    {
        $results = $this->preview($companyId, $from, $to, $userIds);
        $slipIds = [];
        foreach ($results as $result) {
            $slip = $this->slips->createOrUpdateForEmployee([
                'company_id' => $companyId,
                'user_id' => $result['user_id'],
                'salary_from' => $from->toDateString(),
                'salary_to' => $to->toDateString(),
            ], [
                'gross_salary' => $result['gross_pay'],
                'net_salary' => $result['net_pay'],
                'status' => $options['status'] ?? 'generated',
                'additional_earning' => json_encode($result['lines']),
            ]);
            $slipIds[] = $slip->id;
        }
        return new PayrollRunResult(status: PayrollRunStatus::Previewed, companyId: $companyId, periodFrom: $from->toDateString(), periodTo: $to->toDateString(), processedCount: $results->count(), grossTotal: (float) $results->sum('gross_pay'), netTotal: (float) $results->sum('net_pay'), slipIds: $slipIds);
    }

    private function employees(int $companyId, ?array $userIds = null): Collection
    {
        return User::query()->where('company_id', $companyId)->when($userIds, fn ($query) => $query->whereIn('id', $userIds))->get();
    }
}
