<?php

namespace App\Extensions\TitanLeads\Modules\TitanNexus\Http\Requests\Training;

use Illuminate\Foundation\Http\FormRequest;

class QaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'         => 'required',
            'question'   => ['required', 'string'],
            'answer'     => ['required', 'string'],
        ];
    }
}
