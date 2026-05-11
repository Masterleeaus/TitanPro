<?php

namespace App\Extensions\TitanCommand\System\JobManager\Entities;




namespace App\Extensions\TitanCommand\System\JobManager\Entities;use Illuminate\Database\Eloquent\Model;class AuditLog extends Model{protected $table='fsm_audit_logs';protected $guarded=[];protected $casts=['before'=>'array','after'=>'array'];}
