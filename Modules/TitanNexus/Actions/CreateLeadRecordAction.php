<?php
namespace Modules\TitanNexus\Actions;
use Modules\TitanNexus\Data\LeadRecordData;use Modules\TitanNexus\Models\LeadRecord;use Modules\TitanNexus\Events\LeadRecordCreated;
class CreateLeadRecordAction{public function execute(LeadRecordData $data):LeadRecord{$record=LeadRecord::create($data->toArray());event(new LeadRecordCreated($record));return $record;}}
