<?php

namespace Modules\CleaningJobs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWORequestRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'work_order_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'requested_by_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'channel' => ['sometimes', 'nullable', 'string', 'max:50'],
            'description' => ['sometimes', 'nullable', 'string'],
            'request_detail' => ['sometimes', 'nullable', 'string'],
            'client' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'asset' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'priority' => ['sometimes', 'nullable', 'string', 'max:50'],
            'due_date' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', 'nullable', 'string', 'max:50'],
            'assign' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'notes' => ['sometimes', 'nullable', 'string'],
            'preferred_date' => ['sometimes', 'nullable', 'date'],
            'preferred_time' => ['sometimes', 'nullable', 'string', 'max:50'],
            'preferred_note' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
