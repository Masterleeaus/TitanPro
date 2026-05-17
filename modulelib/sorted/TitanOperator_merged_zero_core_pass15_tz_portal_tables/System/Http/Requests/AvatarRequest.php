<?php

namespace App\Extensions\TitanOperator\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AvatarRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'max:4096'],
        ];
    }
}
