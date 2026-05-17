<?php

declare(strict_types=1);

namespace Modules\Budgeting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateReimbursementBatchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'expense_ids' => ['required', 'array', 'min:1'],
            'expense_ids.*' => ['required', 'integer'],
        ];
    }
}
