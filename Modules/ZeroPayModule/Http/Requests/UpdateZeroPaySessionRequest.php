<?php

namespace Modules\ZeroPayModule\Http\Requests;

class UpdateZeroPaySessionRequest extends StoreZeroPaySessionRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('zeropay.update') ?? false;
    }
}
