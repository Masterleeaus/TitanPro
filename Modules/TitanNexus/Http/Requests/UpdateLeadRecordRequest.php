<?php
namespace Modules\TitanNexus\Http\Requests;
class UpdateLeadRecordRequest extends StoreLeadRecordRequest{public function authorize():bool{return $this->user()?->can('titan_nexus.update')??false;}}
