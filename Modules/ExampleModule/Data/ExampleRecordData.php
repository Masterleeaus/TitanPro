<?php
namespace Modules\ExampleModule\Data;
class ExampleRecordData{public function __construct(public int $companyId,public int $userId,public string $name,public string $status='draft',public array $meta=[]){ } public static function fromArray(array $p):self{return new self((int)$p['company_id'],(int)$p['user_id'],(string)$p['name'],(string)($p['status']??'draft'),(array)($p['meta']??[]));} public function toArray():array{return ['company_id'=>$this->companyId,'user_id'=>$this->userId,'name'=>$this->name,'status'=>$this->status,'meta'=>$this->meta];}}
