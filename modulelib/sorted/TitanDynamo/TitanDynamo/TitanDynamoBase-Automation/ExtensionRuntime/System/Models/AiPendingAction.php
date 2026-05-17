<?php
namespace App\Extensions\TitanPulse\System\Models;
use Illuminate\Database\Eloquent\Model;
class AiPendingAction extends Model { protected $table='tz_ai_pending_actions'; protected $guarded=['id']; protected $casts=['payload_json'=>'array','approved_at'=>'datetime','executed_at'=>'datetime','expires_at'=>'datetime','created_at'=>'datetime','updated_at'=>'datetime']; }
