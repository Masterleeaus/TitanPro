<?php

namespace Modules\JobManager\Entities;



namespace ModulesJobManagerEntities;

namespace Modules\JobManager\Entities;use Illuminate\Database\Eloquent\Model;class AuditLog extends Model{protected $table='fsm_audit_logs';protected $guarded=[];protected $casts=['before'=>'array','after'=>'array'];}
