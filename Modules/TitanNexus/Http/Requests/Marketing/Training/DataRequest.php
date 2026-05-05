<?php

namespace App\Extensions\TitanLeads\Modules\TitanNexus\Http\Requests\Training;

use App\Extensions\TitanLeads\Modules\TitanNexus\Enums\EmbeddingTypeEnum;
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
