<?php
namespace Modules\TitanNexus\Contracts;
interface LeadScorerContract { public function score(array $lead): int; }

