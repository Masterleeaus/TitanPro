<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CleanerApprovalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'decision' => ['required', 'string', 'in:approve,reject,suspend,reactivate'],
            'reason' => ['nullable', 'string', 'max:500'],
            'expires_at' => ['nullable', 'date'],
            'meta' => ['nullable', 'array'],
        ];
    }
}
