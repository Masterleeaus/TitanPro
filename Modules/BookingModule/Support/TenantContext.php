<?php

namespace Modules\BookingModule\Support;

use Illuminate\Support\Facades\Auth;

class TenantContext
{
    public static function companyId(): ?int
    {
        try {
            if (function_exists('company') && company()) {
                return (int) company()->id;
            }
        } catch (\Throwable $e) {
            // Fall back to auth context below.
        }

        return Auth::check() ? (Auth::user()->company_id ?? null) : null;
    }
}
