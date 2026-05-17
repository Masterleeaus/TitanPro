<?php
namespace Modules\ExampleModule\Models;
use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\SoftDeletes;use Modules\ExampleModule\Models\Scopes\TenantScope;
class ExampleRecord extends Model{use SoftDeletes;protected $table='example_records';protected $fillable=['company_id','user_id','name','status','meta'];protected $casts=['meta'=>'array'];protected static function booted():void{static::addGlobalScope(new TenantScope());}public function scopeForCompany($q,int $companyId){return $q->where('company_id',$companyId);}}
