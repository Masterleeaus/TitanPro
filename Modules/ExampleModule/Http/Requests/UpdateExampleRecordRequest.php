<?php
namespace Modules\ExampleModule\Http\Requests;
class UpdateExampleRecordRequest extends StoreExampleRecordRequest{public function authorize():bool{return $this->user()?->can('example.update')??false;}}
