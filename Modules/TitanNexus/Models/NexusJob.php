<?php
namespace Modules\TitanNexus\Models;
use Illuminate\Database\Eloquent\Model;
class NexusJob extends Model { protected $table = 'nexus_jobs'; protected $guarded = []; protected $casts = ['metadata'=>'array','payload'=>'array','due_at'=>'datetime','paid_at'=>'datetime','scheduled_at'=>'datetime','completed_at'=>'datetime']; }
