<?php
namespace App\Extensions\ProductPhotography\Models;
use Illuminate\Database\Eloquent\Model;
class QuoteTemplate extends Model { protected $table='ext_quotemaker_templates'; protected $fillable=['name','vertical','service_type','variation','theme','visual_mode','package_tier','summary','pricing_model','metadata_json','is_active']; }
