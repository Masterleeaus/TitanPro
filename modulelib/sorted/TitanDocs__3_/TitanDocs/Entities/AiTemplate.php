<?php

namespace Modules\TitanDocs\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AiTemplate extends Model
{
    use HasFactory;

    protected $table='ai_templates';
    protected $fillable = [
    	'name',
    	'icon',
    	'description',
        'system_prompt',
        'version',
    	'template_code',
    	'status',
    	'professional',
    	'slug',
    	'category_id',
    	'type',
    	'form_fields',
    	'is_tone',
    ];
    
    
    protected static function newFactory()
    {
        return \Modules\TitanDocs\Database\factories\AiTemplateFactory::new();
    }
}
