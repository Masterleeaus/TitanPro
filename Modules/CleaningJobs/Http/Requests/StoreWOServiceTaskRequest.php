<?php

namespace Modules\CleaningJobs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWOServiceTaskRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'work_order_id' => ['required', 'integer', 'min:1'],
            'service_task_id' => ['nullable', 'integer', 'min:1'],
            'service_part_id' => ['nullable', 'integer', 'min:1'],
            'service_task' => ['nullable', 'string', 'max:255'],
            'duration' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'max:50'],
            'qty' => ['nullable', 'numeric', 'min:0'],
            'rate' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
