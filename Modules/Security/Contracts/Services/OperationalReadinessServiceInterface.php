<?php

namespace Modules\Security\Contracts\Services;

interface OperationalReadinessServiceInterface
{
    public function report(): array;

    public function isReady(): bool;
}
