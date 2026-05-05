<?php

namespace Modules\TitanNexus\Jobs;

class ProcessLeadRecordJob
{
    public function __construct(public int $recordId, public int $companyId){} public function handle(): void {}
}
