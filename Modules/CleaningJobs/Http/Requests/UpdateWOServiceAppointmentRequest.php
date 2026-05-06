<?php

namespace Modules\CleaningJobs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWOServiceAppointmentRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'technician_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'starts_at' => ['sometimes', 'nullable', 'date'],
            'ends_at' => ['sometimes', 'nullable', 'date', 'after_or_equal:starts_at'],
            'location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'nullable', 'string', 'max:50'],
            'start_date' => ['sometimes', 'nullable', 'date'],
            'start_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'end_date' => ['sometimes', 'nullable', 'date'],
            'end_time' => ['sometimes', 'nullable', 'date_format:H:i'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
