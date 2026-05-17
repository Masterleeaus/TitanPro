<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers;

use Illuminate\Http\Request; use Illuminate\Routing\Controller;
use App\Extensions\TitanCommand\System\JobManager\Entities\WOPartUsage;



use Illuminate\Http\Request; use Illuminate\Routing\Controller;
class PartsUsageController extends Controller {
  public function index($id){ $rows=WOPartUsage::where('work_order_id',$id)->latest()->get();
    return view('jobmanager::widgets.parts', ['work_order_id'=>$id,'rows'=>$rows]); }
  public function store(Request $r,$id){
    $data=$r->validate(['item_id'=>['nullable','integer'],'item_name'=>['nullable','string','max:255'],
      'qty'=>['required','numeric','min:0.001'],'unit_price'=>['nullable','numeric','min:0'],
      'source_location'=>['nullable','string','max:255']]); $data['work_order_id']=(int)$id;
    WOPartUsage::create($data); return back()->with('status','Part usage recorded.'); }
  public function destroy($id,$row){ WOPartUsage::where('work_order_id',$id)->where('id',$row)->delete();
    return back()->with('status','Part usage removed.'); } }
