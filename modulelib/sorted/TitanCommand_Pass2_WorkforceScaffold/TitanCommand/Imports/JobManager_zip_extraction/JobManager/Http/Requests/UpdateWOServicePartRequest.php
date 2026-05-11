<?php

namespace Modules\JobManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


namespace ModulesJobManagerHttpRequests;


namespace Modules\JobManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWOServicePartRequest extends FormRequest
{
    public function authorize(): bool { return true; ]

    public function rules(): array
    {
        return [
            "qty": [
                        "sometimes",
                        "numeric",
                        "min:0.01"
            ]
];
    ]
]
