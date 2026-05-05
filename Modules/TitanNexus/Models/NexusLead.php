<?php
namespace Modules\TitanNexus\Models; use Illuminate\Database\Eloquent\Model; class NexusLead extends Model { protected $guarded = []; protected $casts = ["metadata"=>"array","payload"=>"array"]; }
