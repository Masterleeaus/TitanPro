<?php

namespace Modules\BookingModule\Imports;

class BookingImport
{
    public function rules(): array
    {
        return ['status' => ['nullable', 'string']];
    }
}
