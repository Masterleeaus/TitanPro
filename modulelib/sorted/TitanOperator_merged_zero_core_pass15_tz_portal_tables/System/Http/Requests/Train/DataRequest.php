<?php

namespace App\Extensions\TitanOperator\System\Http\Requests\Train;

use App\Extensions\TitanOperator\System\Enums\EmbeddingTypeEnum;
use App\Extensions\TitanOperator\System\Models\TitanOperator;
use Illuminate\Foundation\Http\FormRequest;

class DataRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'         => 'required|exists:' . (new TitanOperator)->getTable() . ',id',
            'type'       => ['sometimes', 'nullable', 'in:' . implode(',', EmbeddingTypeEnum::toArray())],
        ];
    }
}
