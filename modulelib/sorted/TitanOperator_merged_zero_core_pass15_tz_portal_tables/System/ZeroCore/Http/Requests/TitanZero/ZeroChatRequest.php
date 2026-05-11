<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Requests\TitanZero;

use Illuminate\Foundation\Http\FormRequest;

class ZeroChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'intent' => ['required', 'string', 'max:500'],
            'payload' => ['nullable', 'array'],
        ];
    }
}
