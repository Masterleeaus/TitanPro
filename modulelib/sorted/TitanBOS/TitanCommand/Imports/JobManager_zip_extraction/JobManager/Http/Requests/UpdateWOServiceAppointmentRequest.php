<?php

namespace Modules\JobManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


namespace ModulesJobManagerHttpRequests;


namespace Modules\JobManager\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWOServiceAppointmentRequest extends FormRequest
{
    public function authorize(): bool { return true; ]

    public function rules(): array
    {
        return [
            "starts_at": [
                        "sometimes",
                        "date"
            ],
            "ends_at": [
                        "sometimes",
                        "nullable",
                        "date",
                        "after_or_equal:starts_at"
            ],
            "assignee_id": [
                        "sometimes",
                        "nullable",
                        "integer",
                        "min:1"
            ]
];
    ]
]
