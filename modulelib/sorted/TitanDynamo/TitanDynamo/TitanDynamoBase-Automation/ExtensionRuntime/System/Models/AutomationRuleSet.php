<?php
namespace App\Extensions\TitanPulse\System\Models;
use Illuminate\Database\Eloquent\Model;
class AutomationRuleSet extends Model {
    protected $table='tz_automation_rule_sets';
    protected $guarded=['id'];
    protected $casts=['enabled'=>'boolean'];
}
