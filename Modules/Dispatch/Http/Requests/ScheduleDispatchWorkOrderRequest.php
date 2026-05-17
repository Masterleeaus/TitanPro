<?php

declare(strict_types=1);

namespace Modules\Dispatch\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScheduleDispatchWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string,array<int,string>> */
    public function rules(): array
    {
        return [
            'work_order_id' => ['required', 'integer', 'exists:dispatch_work_orders,id'],
            'technician_id' => ['required', 'integer'],
            'starts_at' => ['required', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'shift_id' => ['nullable', 'integer'],
            'customer_location_id' => ['nullable', 'integer'],
            'location' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'dispatch_notes' => ['nullable', 'string'],
        ];
    }
}
