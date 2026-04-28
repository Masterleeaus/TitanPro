<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $job = $this->route('job');

        return $user !== null
            && $job !== null
            && $user->organization_id === $job->organization_id;
    }

    public function rules(): array
    {
        $orgId = $this->user()->organization_id;

        return [
            'customer_id' => ['required', 'integer', Rule::exists('customers', 'id')->where('organization_id', $orgId)],
            'property_id' => ['nullable', 'integer', Rule::exists('properties', 'id')->where('organization_id', $orgId)],
            'job_type_id' => ['nullable', 'integer', Rule::exists('job_types', 'id')->where('organization_id', $orgId)],
            'assigned_to' => ['nullable', 'integer', Rule::exists('users', 'id')->where('organization_id', $orgId)],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'scheduled_at' => ['nullable', 'date'],
            'office_notes' => ['nullable', 'string'],
        ];
    }
}
