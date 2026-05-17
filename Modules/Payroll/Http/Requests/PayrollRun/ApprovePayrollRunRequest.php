<?php

namespace Modules\Payroll\Http\Requests\PayrollRun;

use Illuminate\Foundation\Http\FormRequest;

class ApprovePayrollRunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['comment' => ['nullable', 'string', 'max:1000']];
    }
}
