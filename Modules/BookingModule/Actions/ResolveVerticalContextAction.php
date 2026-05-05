<?php

namespace Modules\BookingModule\Actions;

use Modules\BookingModule\Contracts\VerticalContextResolverContract;

class ResolveVerticalContextAction
{
    public function __construct(protected VerticalContextResolverContract $resolver) {}

    public function execute(?int $companyId = null, ?string $vertical = null): array
    {
        return $this->resolver->resolve($companyId, $vertical);
    }
}
