<?php

namespace App\Extensions\TitanOperator\System\Voice\Http\Requests\Train;

use App\Extensions\TitanOperator\System\Voice\Enums\TrainTypeEnum;
use App\Extensions\TitanOperator\System\Voice\Models\ExtVoiceTitanOperator;
use Illuminate\Foundation\Http\FormRequest;

class DataRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'         => 'required|exists:' . (new ExtVoiceTitanOperator)->getTable() . ',id',
            'type'       => ['sometimes', 'nullable', 'in:' . implode(',', TrainTypeEnum::toArray())],
        ];
    }
}
