<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $property = $this->route('property');

        return $user !== null
            && $property !== null
            && $user->organization_id === $property->organization_id;
    }

    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:255'],
            'address_line1' => ['required', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:50'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'size:2'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
