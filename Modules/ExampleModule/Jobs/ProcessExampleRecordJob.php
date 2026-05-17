<?php

namespace Modules\ExampleModule\Jobs;

class ProcessExampleRecordJob
{
    public function __construct(public int $recordId, public int $companyId){} public function handle(): void {}
}
