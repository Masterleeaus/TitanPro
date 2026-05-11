<?php

namespace App\Extensions\TitanOperator\System\Http\Requests\Train;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use App\Extensions\TitanOperator\System\Models\TitanOperatorEmbedding;
use Illuminate\Foundation\Http\FormRequest;

class EmbedingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'     => 'required|exists:' . (new TitanOperator)->getTable() . ',id',
            'data'   => 'required|array',
            'data.*' => 'required|exists:' . (new TitanOperatorEmbedding)->getTable() . ',id',
        ];
    }
}
