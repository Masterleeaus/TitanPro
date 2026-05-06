<?php

namespace App\Extensions\TitanLeads\Modules\TitanNexus\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class TextRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'      => 'required',
            'title'   => ['required', 'string'],
            'content' => ['required', 'string'],
        ];
    }
}
