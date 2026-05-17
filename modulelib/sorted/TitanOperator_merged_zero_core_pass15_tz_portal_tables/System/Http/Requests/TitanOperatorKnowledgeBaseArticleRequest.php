<?php

namespace App\Extensions\TitanOperator\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TitanOperatorKnowledgeBaseArticleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id'    => ['required', 'integer'],
            'title'      => ['required', 'string'],
            'description'=> ['required', 'string'],
            'content'    => ['sometimes', 'nullable', 'string'],
            'is_featured'=> ['boolean'],
            'operators'   => ['sometimes', 'nullable', 'array'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'user_id'     => $this->user()->getKey(),
            'is_featured' => $this->has('is_featured'),
        ]);
    }
}
