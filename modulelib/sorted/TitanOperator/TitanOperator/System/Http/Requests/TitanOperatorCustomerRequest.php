<?php

namespace App\Extensions\TitanOperator\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TitanOperatorCustomerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'     => ['required', 'string'],
            'email'    => ['required', 'string'],
            'phone'    => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void {}
}
