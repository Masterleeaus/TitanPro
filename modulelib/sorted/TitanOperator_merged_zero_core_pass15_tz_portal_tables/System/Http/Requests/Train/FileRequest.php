<?php

namespace App\Extensions\TitanOperator\System\Http\Requests\Train;

use App\Extensions\TitanOperator\System\Models\TitanOperator;
use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id'     => 'required|exists:' . (new TitanOperator)->getTable() . ',id',
            'file'   => 'required|file',
        ];
    }
}
