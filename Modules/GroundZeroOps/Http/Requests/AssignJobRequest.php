<?php

namespace Modules\GroundZeroOps\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignJobRequest extends FormRequest
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
            'job_id' => ['required', 'integer', 'min:1'],
            'technician_id' => ['required', 'integer', 'min:1'],
        ];
    }
}
