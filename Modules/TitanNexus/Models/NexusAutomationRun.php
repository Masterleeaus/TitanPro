<?php
namespace Modules\TitanNexus\Models;
use Illuminate\Database\Eloquent\Model;
class NexusAutomationRun extends Model { protected $table = 'nexus_automation_runs'; protected $guarded = []; protected $casts = ['metadata'=>'array','payload'=>'array','due_at'=>'datetime','paid_at'=>'datetime','scheduled_at'=>'datetime','completed_at'=>'datetime']; }
