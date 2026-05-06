<?php

namespace Modules\CleaningJobs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWORequestRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'work_order_id' => ['nullable', 'integer', 'min:1'],
            'requested_by_id' => ['nullable', 'integer', 'min:1'],
            'channel' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'request_detail' => ['nullable', 'string'],
            'client' => ['nullable', 'integer', 'min:1'],
            'asset' => ['nullable', 'integer', 'min:1'],
            'priority' => ['nullable', 'string', 'max:50'],
            'due_date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'max:50'],
            'assign' => ['nullable', 'integer', 'min:1'],
            'notes' => ['nullable', 'string'],
            'preferred_date' => ['nullable', 'date'],
            'preferred_time' => ['nullable', 'string', 'max:50'],
            'preferred_note' => ['nullable', 'string'],
        ];
    }
}
