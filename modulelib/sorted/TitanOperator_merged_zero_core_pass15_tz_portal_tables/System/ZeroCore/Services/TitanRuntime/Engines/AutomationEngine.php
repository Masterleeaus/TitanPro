<?php
namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Engines;
class AutomationEngine{
public function run(array $event,array $context=[]):array{
$actions=[];
if(($event['type']??'')==='chat.prompt'){ $actions[]=['type'=>'log','message'=>'Prompt processed']; }
if(str_contains(strtolower($context['prompt']??''),'invoice')){
$actions[]=['type'=>'trigger','name'=>'create_invoice'];
}
return ['actions'=>$actions,'count'=>count($actions)];
}}