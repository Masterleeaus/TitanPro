<?php

namespace App\Extensions\TitanOperator\System\Http\Requests\Train;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use Illuminate\Foundation\Http\FormRequest;

class TrainUrlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'     => 'required|exists:' . (new TitanOperator)->getTable() . ',id',
            'url'    => ['required', 'url'],
            'single' => ['required', 'in:1,0'],
        ];
    }
}
