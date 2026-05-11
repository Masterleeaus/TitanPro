<?php

namespace App\Extensions\TitanRewind\System\Http\Requests\Training;

use App\Extensions\TitanRewind\System\Enums\EmbeddingTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class DataRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'         => 'required',
            'type'       => ['sometimes', 'nullable', 'in:' . implode(',', EmbeddingTypeEnum::toArray())],
        ];
    }
}
