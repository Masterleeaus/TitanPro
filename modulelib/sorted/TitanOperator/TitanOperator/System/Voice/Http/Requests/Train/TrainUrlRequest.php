<?php

namespace App\Extensions\TitanOperator\System\Voice\Http\Requests\Train;

use App\Extensions\TitanOperator\System\Voice\Models\ExtVoiceTitanOperator;
use Illuminate\Foundation\Http\FormRequest;

class TrainUrlRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'     => 'required|exists:' . (new ExtVoiceTitanOperator)->getTable() . ',id',
            'url'    => ['required', 'url'],
            'single' => ['required', 'in:1,0'],
        ];
    }
}
