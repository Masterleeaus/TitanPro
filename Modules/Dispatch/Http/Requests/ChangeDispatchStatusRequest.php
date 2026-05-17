<?php

declare(strict_types=1);

namespace Modules\Dispatch\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ChangeDispatchStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string,array<int,mixed>> */
    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::in(config('dispatch.statuses.assignment', ['pending', 'accepted', 'en_route', 'arrived', 'in_progress', 'completed', 'cancelled']))],
            'notes' => ['nullable', 'string'],
        ];
    }
}
