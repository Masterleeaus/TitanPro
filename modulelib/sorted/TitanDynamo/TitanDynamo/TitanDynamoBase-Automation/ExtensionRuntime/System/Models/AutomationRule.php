<?php
namespace App\Extensions\TitanPulse\System\Models;
use Illuminate\Database\Eloquent\Model;
class AutomationRule extends Model { protected $table='tz_automation_rules'; protected $guarded=['id']; protected $casts=['is_enabled'=>'boolean','trigger_config'=>'array','action_config'=>'array']; }
