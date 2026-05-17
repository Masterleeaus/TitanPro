<?php
namespace Modules\ExampleModule\Http\Controllers;
use Illuminate\Routing\Controller;use Modules\ExampleModule\Services\Contracts\ExampleModuleServiceContract;
class ExampleController extends Controller{public function index(ExampleModuleServiceContract $service){return view('example-module::index',['records'=>$service->listForCompany(auth()->user()->company_id)]);}public function store(){/* validate and call CreateExampleRecordAction */}}
