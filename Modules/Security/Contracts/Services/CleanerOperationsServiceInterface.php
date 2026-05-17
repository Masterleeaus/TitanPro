<?php

namespace Modules\Security\Contracts\Services;

use Modules\Security\Entities\Cleaner;
use Modules\Security\Entities\CleanerSite;
use Modules\Security\Entities\CleanerSiteLog;

interface CleanerOperationsServiceInterface
{
    public function register(array $data): Cleaner;

    public function registerSite(array $data): CleanerSite;

    public function approve(Cleaner $cleaner, ?int $userId = null): Cleaner;

    public function decide(Cleaner $cleaner, string $decision, array $data = [], ?int $userId = null): Cleaner;

    public function forceCheckOut(Cleaner $cleaner, array $data = [], ?int $userId = null): CleanerSiteLog;

    public function checkIn(Cleaner $cleaner, array $data = [], ?int $userId = null): CleanerSiteLog;

    public function checkOut(Cleaner $cleaner, array $data = [], ?int $userId = null): CleanerSiteLog;

    public function activeSites(): array;

    public function dashboard(): array;
}
