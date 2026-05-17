<?php

namespace Modules\Security\Contracts\Services;

interface SecurityModuleServiceInterface
{
    /**
     * Return aggregate counters for the Security module dashboard/API.
     */
    public function dashboard(): array;

    /**
     * Return lightweight module health diagnostics.
     */
    public function health(): array;

    /**
     * Return configured features keyed by capability.
     */
    public function features(): array;

    /**
     * Return configured permissions grouped for UI/API consumers.
     */
    public function permissions(): array;

    /**
     * Return compact operational status for UI widgets and checks.
     */
    public function status(): array;

    /**
     * Return deep diagnostics for install verification and support.
     */
    public function diagnostics(): array;
}
