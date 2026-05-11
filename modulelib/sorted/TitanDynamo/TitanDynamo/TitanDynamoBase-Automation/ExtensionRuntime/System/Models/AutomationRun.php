<?php
namespace App\Extensions\TitanPulse\System\Models;
use Illuminate\Database\Eloquent\Model;
class AutomationRun extends Model { public $timestamps=false; protected $table='tz_automation_runs'; protected $guarded=['id']; protected $casts=['context'=>'array','result'=>'array','started_at'=>'datetime','finished_at'=>'datetime','created_at'=>'datetime']; }
