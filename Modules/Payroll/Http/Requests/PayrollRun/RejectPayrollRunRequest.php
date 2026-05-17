<?php

namespace Modules\Payroll\Http\Requests\PayrollRun;

use Illuminate\Foundation\Http\FormRequest;

class RejectPayrollRunRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return ['comment' => ['required', 'string', 'max:1000']];
    }
}
