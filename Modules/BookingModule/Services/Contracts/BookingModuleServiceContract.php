<?php

namespace Modules\BookingModule\Services\Contracts;

interface BookingModuleServiceContract
{
    public function health(?int $companyId = null): array;

    public function overview(?int $companyId = null): array;
}
