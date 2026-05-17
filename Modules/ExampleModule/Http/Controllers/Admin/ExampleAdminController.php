<?php
namespace Modules\ExampleModule\Http\Controllers\Admin;
use Illuminate\Routing\Controller;
class ExampleAdminController extends Controller{public function dashboard(){return view('example-module::admin.dashboard');}}
