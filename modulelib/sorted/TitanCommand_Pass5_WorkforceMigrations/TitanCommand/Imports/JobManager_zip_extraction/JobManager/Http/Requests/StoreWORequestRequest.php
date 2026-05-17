<?php

namespace Modules\JobManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


namespace ModulesJobManagerHttpRequests;


namespace Modules\JobManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWORequestRequest extends FormRequest
{
    public function authorize(): bool { return true; ]

    public function rules(): array
    {
        return [
            "customer_id": [
                        "required",
                        "integer",
                        "min:1"
            ],
            "summary": [
                        "required",
                        "string",
                        "max:500"
            ],
            "requested_at": [
                        "nullable",
                        "date"
            ]
];
    ]
]
