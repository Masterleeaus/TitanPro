<?php

if (! function_exists('tenant_company_id')) {
    function tenant_company_id(): ?int
    {
        return auth()->user()?->company_id;
    }
}
