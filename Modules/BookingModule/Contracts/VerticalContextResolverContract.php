<?php

namespace Modules\BookingModule\Contracts;

interface VerticalContextResolverContract
{
    public function resolve(?int $companyId = null, ?string $vertical = null): array;
}
