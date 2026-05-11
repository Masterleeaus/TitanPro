<?php

namespace Modules\JobManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\JobManager\Entities\ServiceTask;


namespace ModulesJobManagerHttpControllers;


namespace Modules\JobManager\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\JobManager\Entities\ServiceTask;

class ServiceTaskController extends Controller
{
    public function index() { return view('jobmanager::crud.index', ['resource' => 'ServiceTaskController']); }
    public function create() { return view('jobmanager::crud.create', ['resource' => 'ServiceTaskController']); }
    public function store(Request $request) { /* TODO: validate + create */ return back()->with('status', 'created'); }
    public function show($id) { return view('jobmanager::crud.show', compact('id')); }
    public function edit($id) { return view('jobmanager::crud.edit', compact('id')); }
    public function update(Request $request, $id) { /* TODO: validate + update */ return back()->with('status', 'updated'); }
    public function destroy($id) { /* TODO: delete */ return back()->with('status', 'deleted'); }
}
