<?php
namespace Modules\TitanNexus\Actions;
use Modules\TitanNexus\Models\LeadRecord;
class DeleteLeadRecordAction{public function execute(LeadRecord $record):bool{return (bool)$record->delete();}}
