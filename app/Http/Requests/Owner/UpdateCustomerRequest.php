<?php

namespace App\Http\Requests\Owner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $customer = $this->route('customer');

        return $user !== null
            && $customer !== null
            && $user->organization_id === $customer->organization_id;
    }

    public function rules(): array
    {
        $orgId = $this->user()->organization_id;
        $customerId = $this->route('customer')?->id;

        $uniqueRule = Rule::unique('customers', 'email')->where('organization_id', $orgId);
        if ($customerId !== null) {
            $uniqueRule = $uniqueRule->ignore($customerId);
        }

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', $uniqueRule],
            'phone' => ['nullable', 'string', 'max:50'],
            'mobile' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
