<?php
namespace App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Engines;
class RuntimeOrchestrator{
public function __construct(
protected PluginDispatchEngine $plugins,
protected AssistantDispatchEngine $assistants,
protected CanvasRenderEngine $canvas,
protected MemoryEngine $memory,
protected AutomationEngine $automation){}
public function handle(string $prompt):array{
$memory=$this->memory->buildContext();
$execution=$this->plugins->execute($prompt);
$assistant=$this->assistants->assign($execution['plugin']??[]);
$canvas=$this->canvas->render($execution['result']??[]);
$automation=$this->automation->run(['type'=>'chat.prompt'],['prompt'=>$prompt]);
return[
'assistant'=>$assistant,
'plugin'=>$execution['plugin']??null,
'result'=>$execution['result']??null,
'canvas'=>$canvas,
'memory'=>$memory,
'automation'=>$automation
];
}}