<?php
namespace Modules\TitanNexus\Database\States;
final class QualifiedLeadState { public function apply(array $lead): array { return $lead + ['status'=>'qualified']; } }

