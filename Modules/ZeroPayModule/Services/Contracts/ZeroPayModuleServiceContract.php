<?php

namespace Modules\ZeroPayModule\Services\Contracts;

interface ZeroPayModuleServiceContract
{
    public function listForCompany(int $companyId, int $perPage = 25);
}
