<?php

namespace Modules\Security\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CleanerCheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'site_id' => ['nullable', 'integer', 'exists:security_cleaner_sites,id'],
            'site_name' => ['nullable', 'string', 'max:120'],
            'checkpoint' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
