<?php

namespace Modules\ZeroPayModule\Jobs;

class ProcessZeroPaySessionJob
{
    public function __construct(public int $recordId, public int $companyId) {}

    public function handle(): void {}
}
