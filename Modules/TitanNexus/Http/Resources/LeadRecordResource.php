<?php
namespace Modules\TitanNexus\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
class LeadRecordResource extends JsonResource{public function toArray($request):array{return ['id'=>$this->id,'company_id'=>$this->company_id,'name'=>$this->name,'status'=>$this->status];}}
