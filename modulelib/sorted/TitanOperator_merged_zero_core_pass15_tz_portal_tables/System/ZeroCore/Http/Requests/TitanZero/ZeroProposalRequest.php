<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Requests\TitanZero;

use Illuminate\Foundation\Http\FormRequest;

class ZeroProposalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:pending_review,approved,rejected,modified,deferred'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
