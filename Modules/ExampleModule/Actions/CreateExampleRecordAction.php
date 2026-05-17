<?php
namespace Modules\ExampleModule\Actions;
use Modules\ExampleModule\Data\ExampleRecordData;use Modules\ExampleModule\Models\ExampleRecord;use Modules\ExampleModule\Events\ExampleCreated;
class CreateExampleRecordAction{public function execute(ExampleRecordData $data):ExampleRecord{$record=ExampleRecord::create($data->toArray());event(new ExampleCreated($record));return $record;}}
