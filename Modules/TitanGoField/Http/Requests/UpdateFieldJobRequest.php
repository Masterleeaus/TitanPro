<?php

namespace Modules\TitanGoField\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFieldJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('job'));
    }

    public function rules(): array
    {
        return [
            'type_id'        => ['nullable', 'integer'],
            'client_id'      => ['nullable', 'integer'],
            'technician_id'  => ['nullable', 'integer'],
            'priority'       => ['nullable', 'string', 'in:low,normal,high,urgent'],
            'description'    => ['nullable', 'string', 'max:5000'],
            'notes'          => ['nullable', 'string'],
            'scheduled_start' => ['nullable', 'date'],
            'scheduled_end'  => ['nullable', 'date', 'after_or_equal:scheduled_start'],
            'due_at'         => ['nullable', 'date'],
        ];
    }
}
