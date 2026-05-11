<?php
namespace App\Extensions\TitanPulse\System\Models;
use Illuminate\Database\Eloquent\Model;
class Signal extends Model { public $timestamps=false; protected $table='tz_signals'; protected $guarded=['id']; protected $casts=['payload_json'=>'array','signal_at'=>'datetime','created_at'=>'datetime']; }
