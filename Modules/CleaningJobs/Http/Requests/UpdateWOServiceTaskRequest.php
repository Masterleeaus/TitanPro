<?php

namespace Modules\CleaningJobs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWOServiceTaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'service_task_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'service_part_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'service_task' => ['sometimes', 'nullable', 'string', 'max:255'],
            'duration' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'nullable', 'string', 'max:50'],
            'qty' => ['sometimes', 'numeric', 'min:0'],
            'rate' => ['sometimes', 'numeric', 'min:0'],
        ];
    }
}
