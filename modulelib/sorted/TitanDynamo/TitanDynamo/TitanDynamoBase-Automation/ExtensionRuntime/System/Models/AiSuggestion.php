<?php
namespace App\Extensions\TitanPulse\System\Models;
use Illuminate\Database\Eloquent\Model;
class AiSuggestion extends Model { protected $table='tz_ai_suggestions'; protected $guarded=['id']; protected $casts=['payload_json'=>'array','expires_at'=>'datetime','actioned_at'=>'datetime','created_at'=>'datetime','updated_at'=>'datetime']; }
