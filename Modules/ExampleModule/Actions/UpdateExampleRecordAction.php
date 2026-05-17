<?php
namespace Modules\ExampleModule\Actions;
use Modules\ExampleModule\Data\ExampleRecordData;use Modules\ExampleModule\Models\ExampleRecord;
class UpdateExampleRecordAction{public function execute(ExampleRecord $record,ExampleRecordData $data):ExampleRecord{$record->update($data->toArray());return $record->refresh();}}
