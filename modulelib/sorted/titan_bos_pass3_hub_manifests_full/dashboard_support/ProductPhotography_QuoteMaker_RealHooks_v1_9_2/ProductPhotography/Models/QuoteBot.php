<?php
namespace App\Extensions\ProductPhotography\Models;
use Illuminate\Database\Eloquent\Model;
class QuoteBot extends Model { protected $table='ext_quotemaker_quote_bots'; protected $fillable=['template_id','name','follow_up_delay_hours','follow_up_channel','auto_create_booking','auto_create_job','auto_negotiate','negotiation_rules_json','status']; }
