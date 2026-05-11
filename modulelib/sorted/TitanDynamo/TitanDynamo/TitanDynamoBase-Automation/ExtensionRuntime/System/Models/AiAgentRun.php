<?php
namespace App\Extensions\TitanPulse\System\Models;
use Illuminate\Database\Eloquent\Model;
class AiAgentRun extends Model { protected $table='tz_ai_agent_runs'; protected $guarded=['id']; protected $casts=['result_json'=>'array','started_at'=>'datetime','ended_at'=>'datetime','created_at'=>'datetime','updated_at'=>'datetime']; }
