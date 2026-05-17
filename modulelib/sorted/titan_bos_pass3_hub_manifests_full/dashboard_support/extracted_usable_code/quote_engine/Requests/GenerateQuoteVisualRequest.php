<?php

namespace App\Extensions\ProductPhotography\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateQuoteVisualRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'service_type' => ['nullable', 'string', 'max:120'],
            'site_type' => ['nullable', 'string', 'max:120'],
            'work_area' => ['nullable', 'string', 'max:120'],
            'scope_notes' => ['nullable', 'string', 'max:5000'],
            'visual_mode' => ['required', 'string', 'max:60'],
            'package_tier' => ['nullable', 'string', 'max:40'],
            'quote_reference' => ['nullable', 'string', 'max:120'],
            'customer_context' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
