<?php

namespace Modules\TitanNexus\Automation\Pipelines;

/** Runs target-to-booking nurture pipeline. */
class LeadNurturePipeline
{
    public function handle(): array
    {
        return ['enabled' => true, 'purpose' => 'Runs target-to-booking nurture pipeline.'];
    }
}
