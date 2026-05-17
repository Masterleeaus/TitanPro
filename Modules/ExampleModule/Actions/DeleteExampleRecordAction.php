<?php
namespace Modules\ExampleModule\Actions;
use Modules\ExampleModule\Models\ExampleRecord;
class DeleteExampleRecordAction{public function execute(ExampleRecord $record):bool{return (bool)$record->delete();}}
