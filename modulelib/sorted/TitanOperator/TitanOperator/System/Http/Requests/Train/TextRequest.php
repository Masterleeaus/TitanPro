<?php

namespace App\Extensions\TitanOperator\System\Http\Requests\Train;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use Illuminate\Foundation\Http\FormRequest;

class TextRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'      => 'required|exists:' . (new TitanOperator)->getTable() . ',id',
            'title'   => ['required', 'string'],
            'content' => ['required', 'string'],
        ];
    }
}
