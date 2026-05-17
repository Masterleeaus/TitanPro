<?php

namespace Modules\Security\Contracts\Services;

interface CleanerReportingServiceInterface
{
    public function daily(array $filters = []): array;

    public function siteSummary(array $filters = []): array;

    public function exceptions(array $filters = []): array;
}
