<?php
namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Engines;
class MemoryEngine{
public function buildContext():array{
return[
'site_memory'=>['summary'=>'Default site access + preferences'],
'job_memory'=>['summary'=>'Recent job patterns']
];
}}