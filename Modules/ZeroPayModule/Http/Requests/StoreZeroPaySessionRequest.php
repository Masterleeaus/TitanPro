<?php

namespace Modules\ZeroPayModule\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreZeroPaySessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('zeropay.create') ?? false;
    }

    public function rules(): array
    {
        return ['name' => ['required', 'string', 'max:255'], 'status' => ['nullable', 'string'], 'meta' => ['nullable', 'array']];
    }

    public function toDataArray(): array
    {
        return $this->validated() + ['company_id' => $this->user()->company_id, 'user_id' => $this->user()->id];
    }
}
