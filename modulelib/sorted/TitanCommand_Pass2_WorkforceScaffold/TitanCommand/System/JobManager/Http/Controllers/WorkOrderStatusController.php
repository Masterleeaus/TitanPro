<?php

namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers;




namespace App\Extensions\TitanCommand\System\JobManager\Http\Controllers; use Illuminate\Routing\Controller; use Illuminate\Support\Facades\DB; use App\Extensions\TitanCommand\System\JobManager\Listeners\WorkOrderAutoInvoiceListener;
class WorkOrderStatusController extends Controller{
  public function complete($id){
    $before=(array)DB::table('work_orders')->where('id',$id)->first();
    DB::table('work_orders')->where('id',$id)->update(['status'=>'completed','updated_at'=>now()]);
    (new WorkOrderAutoInvoiceListener)->handle($id);
    $after=(array)DB::table('work_orders')->where('id',$id)->first();
    if(DB::getSchemaBuilder()->hasTable('fsm_audit_logs')){
      DB::table('fsm_audit_logs')->insert(['entity_type'=>'work_order','entity_id'=>$id,'user_id'=>auth()->id(),'action'=>'update_status','before'=>json_encode($before),'after'=>json_encode($after),'ip'=>request()->ip(),'created_at'=>now(),'updated_at'=>now()]);
    }
    return back()->with('status','Completed.');
  }
}
