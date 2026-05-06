<?php

namespace Modules\TitanNexus\Contracts;

interface LeadSourceContract
{
    public function discover(array $criteria): iterable;
    public function normalize(array $rawLead): array;
}
