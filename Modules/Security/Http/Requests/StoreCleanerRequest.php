<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCleanerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:120'],
            'site_id' => ['nullable', 'integer', 'exists:security_cleaner_sites,id'],
            'vendor_name' => ['nullable', 'string', 'max:120'],
            'site_name' => ['nullable', 'string', 'max:120'],
            'status' => ['nullable', Rule::in(config('security_cleaners.statuses', ['pending', 'active', 'suspended', 'archived']))],
            'meta' => ['nullable', 'array'],
        ];
    }
}
