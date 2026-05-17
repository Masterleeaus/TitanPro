<?php

namespace Modules\Payroll\Contracts\Services;

interface CleaningPayrollSettingsServiceContract
{
    public function defaults(?int $companyId = null): array;

    public function normalize(array $settings, ?int $companyId = null): array;

    public function validate(array $settings): array;
}
