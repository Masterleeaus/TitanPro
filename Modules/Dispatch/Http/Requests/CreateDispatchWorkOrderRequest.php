<?php

declare(strict_types=1);

namespace Modules\Dispatch\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateDispatchWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string,array<int,mixed>> */
    public function rules(): array
    {
        return [
            'company_id' => ['nullable', 'integer'],
            'customer_id' => ['nullable', 'integer'],
            'customer_location_id' => ['nullable', 'integer', 'exists:customer_locations,id'],
            'technician_id' => ['nullable', 'integer'],
            'title' => ['required', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', Rule::in(config('dispatch.statuses.work_order', ['draft', 'scheduled', 'dispatched', 'in_progress', 'completed', 'cancelled']))],
            'priority' => ['nullable', 'string', Rule::in(['low', 'normal', 'high', 'urgent'])],
            'description' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'estimated_hours' => ['nullable', 'numeric', 'min:0'],
            'scheduled_for' => ['nullable', 'date'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
