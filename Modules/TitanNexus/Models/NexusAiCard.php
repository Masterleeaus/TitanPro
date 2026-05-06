<?php
namespace Modules\TitanNexus\Models; use Illuminate\Database\Eloquent\Model; class NexusAiCard extends Model { protected $guarded = []; protected $casts = ["metadata"=>"array","payload"=>"array"]; }
