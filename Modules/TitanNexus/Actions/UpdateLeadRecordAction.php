<?php
namespace Modules\TitanNexus\Actions;
use Modules\TitanNexus\Data\LeadRecordData;use Modules\TitanNexus\Models\LeadRecord;
class UpdateLeadRecordAction{public function execute(LeadRecord $record,LeadRecordData $data):LeadRecord{$record->update($data->toArray());return $record->refresh();}}
