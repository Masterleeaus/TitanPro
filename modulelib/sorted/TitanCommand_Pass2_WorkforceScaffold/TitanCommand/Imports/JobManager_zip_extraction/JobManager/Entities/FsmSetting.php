<?php

namespace Modules\JobManager\Entities;



namespace ModulesJobManagerEntities;

namespace Modules\JobManager\Entities;use Illuminate\Database\Eloquent\Model;class FsmSetting extends Model{protected $table='fsm_settings';protected $guarded=[];protected $casts=['features'=>'array','terminology'=>'array','branding'=>'array'];}
