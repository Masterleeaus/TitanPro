<?php

namespace Modules\Payroll\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Modules\Payroll\Contracts\Repositories\PayrollRunRepositoryContract;
use Modules\Payroll\Contracts\Services\PayrollApprovalServiceContract;
use Modules\Payroll\Http\Requests\PayrollRun\ApprovePayrollRunRequest;
use Modules\Payroll\Http\Requests\PayrollRun\RejectPayrollRunRequest;
use Modules\Payroll\Http\Resources\PayrollRunDetailResource;

class PayrollApprovalController extends Controller
{
    public function __construct(
        private readonly PayrollRunRepositoryContract $runs,
        private readonly PayrollApprovalServiceContract $approvals,
    ) {}

    public function submit(int $run): JsonResponse
    {
        $payrollRun = $this->runs->findForCompany((int) company()->id, $run);
        abort_if(! $payrollRun, 404, 'Payroll run not found.');

        return PayrollRunDetailResource::make($this->approvals->submit($payrollRun, user()?->id)->load('approvals'))->response();
    }

    public function approve(ApprovePayrollRunRequest $request, int $run): JsonResponse
    {
        $payrollRun = $this->runs->findForCompany((int) company()->id, $run);
        abort_if(! $payrollRun, 404, 'Payroll run not found.');

        return PayrollRunDetailResource::make($this->approvals->approve($payrollRun, user()->id, $request->string('comment')->toString())->load('approvals'))->response();
    }

    public function reject(RejectPayrollRunRequest $request, int $run): JsonResponse
    {
        $payrollRun = $this->runs->findForCompany((int) company()->id, $run);
        abort_if(! $payrollRun, 404, 'Payroll run not found.');

        return PayrollRunDetailResource::make($this->approvals->reject($payrollRun, user()->id, $request->string('comment')->toString())->load('approvals'))->response();
    }
}
