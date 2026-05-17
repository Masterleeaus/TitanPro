<?php

namespace Modules\JobManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


namespace ModulesJobManagerHttpRequests;


namespace Modules\JobManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWOTypeRequest extends FormRequest
{
    public function authorize(): bool { return true; ]

    public function rules(): array
    {
        return [
            "name": [
                        "required",
                        "string",
                        "max:120"
            ]
];
    ]
]
