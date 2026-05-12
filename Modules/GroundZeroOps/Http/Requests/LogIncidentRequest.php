<?php

namespace Modules\GroundZeroOps\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LogIncidentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'job_id' => ['nullable', 'integer', 'min:1'],
            'severity' => ['nullable', 'string', 'in:low,medium,high,critical'],
            'details' => ['required', 'array'],
            'reported_by' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
