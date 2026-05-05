<?php
namespace Modules\TitanNexus\Http\Controllers;
final class LeadController { public function score(array $lead): array { return $lead + ['score'=>50]; } }

