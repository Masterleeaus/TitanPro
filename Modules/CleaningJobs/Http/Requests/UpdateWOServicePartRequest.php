<?php

namespace Modules\CleaningJobs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWOServicePartRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'service_part_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'quantity' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'qty' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'amount' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'type' => ['sometimes', 'nullable', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
