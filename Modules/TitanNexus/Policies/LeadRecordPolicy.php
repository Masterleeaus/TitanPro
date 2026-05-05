<?php
namespace Modules\TitanNexus\Policies;
class LeadRecordPolicy{public function viewAny($user):bool{return $user->can('titan_nexus.view');}public function view($user,$record):bool{return $user->can('titan_nexus.view')&&$record->company_id===$user->company_id;}public function create($user):bool{return $user->can('titan_nexus.create');}public function update($user,$record):bool{return $user->can('titan_nexus.update')&&$record->company_id===$user->company_id;}public function delete($user,$record):bool{return $user->can('titan_nexus.delete')&&$record->company_id===$user->company_id;}}
