<?php
namespace App\Extensions\TitanOperator\System\ZeroCore\Http\Controllers\TitanRuntime;
use App\Http\Controllers\Controller;
use App\Extensions\TitanOperator\System\ZeroCore\Services\TitanRuntime\Engines\RuntimeOrchestrator;
use Illuminate\Http\Request;
class TitanRuntimeApiController extends Controller{
public function dispatch(Request $request,RuntimeOrchestrator $orchestrator){
$prompt=(string)$request->input('prompt','');
return response()->json($orchestrator->handle($prompt));
}}