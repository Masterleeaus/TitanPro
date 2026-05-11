<?php

namespace App\Extensions\MarketingBot\System\Http\Requests\Voice;

use Illuminate\Foundation\Http\FormRequest;

class CallbackCallRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'callback_due_at' => ['nullable', 'date'],
        ];
    }
}
