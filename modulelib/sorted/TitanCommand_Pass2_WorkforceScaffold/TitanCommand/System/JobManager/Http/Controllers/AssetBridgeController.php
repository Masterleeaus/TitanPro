<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers;

use Illuminate\Http\Request; use Illuminate\Routing\Controller;
use App\Extensions\TitanCommand\System\JobManager\Entities\WOAsset;



use Illuminate\Http\Request; use Illuminate\Routing\Controller;
class AssetBridgeController extends Controller {
  public function index($id){ $rows=WOAsset::where('work_order_id',$id)->get();
    return view('jobmanager::widgets.assets',['work_order_id'=>$id,'rows'=>$rows]); }
  public function store(Request $r,$id){ $data=$r->validate(['asset_id'=>['required','integer','min:1']]);
    $data['work_order_id']=(int)$id; WOAsset::firstOrCreate($data);
    return back()->with('status','Asset linked.'); }
  public function destroy($id,$row){ WOAsset::where('work_order_id',$id)->where('id',$row)->delete();
    return back()->with('status','Asset unlinked.'); } }
