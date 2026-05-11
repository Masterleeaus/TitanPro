<?php

namespace App\Extensions\TitanOperator\System\Voice\Http\Requests\Train;

use App\Extensions\TitanOperator\System\Voice\Models\ExtVoiceTitanOperator;
use App\Extensions\TitanOperator\System\Voice\Models\ExtVoiceoperatorTrain;
use Illuminate\Foundation\Http\FormRequest;

class TrainRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'     => 'required|exists:' . (new ExtVoiceTitanOperator)->getTable() . ',id',
            'data'   => 'required|array',
            'data.*' => 'required|exists:' . (new ExtVoiceoperatorTrain)->getTable() . ',id',
        ];
    }
}
