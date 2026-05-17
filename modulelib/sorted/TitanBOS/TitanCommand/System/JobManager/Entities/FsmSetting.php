<?php

namespace App\Extensions\TitanCommand\System\JobManager\Entities;




namespace App\Extensions\TitanCommand\System\JobManager\Entities;use Illuminate\Database\Eloquent\Model;class FsmSetting extends Model{protected $table='fsm_settings';protected $guarded=[];protected $casts=['features'=>'array','terminology'=>'array','branding'=>'array'];}
