<?php
namespace Modules\ExampleModule\Policies;
class ExampleRecordPolicy{public function viewAny($user):bool{return $user->can('example.view');}public function view($user,$record):bool{return $user->can('example.view')&&$record->company_id===$user->company_id;}public function create($user):bool{return $user->can('example.create');}public function update($user,$record):bool{return $user->can('example.update')&&$record->company_id===$user->company_id;}public function delete($user,$record):bool{return $user->can('example.delete')&&$record->company_id===$user->company_id;}}
