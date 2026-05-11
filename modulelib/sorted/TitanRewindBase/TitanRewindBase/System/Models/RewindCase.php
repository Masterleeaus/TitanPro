<?php

namespace App\Extensions\TitanPay\System\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RewindCase extends Model
{
    protected $table = 'boxed_automation_cases';

    protected $fillable = [
        'company_id','user_id',
        'title','status','severity',
        'source_type','source_id',
        'detected_at','meta_json',
        'resolved_at','resolved_by_type','resolved_by_id',
    ];

    protected $casts = [
        'detected_at' => 'datetime',
        'resolved_at' => 'datetime',
        'meta_json' => 'array',
    ];

    public function events(): HasMany
    {
        return $this->hasMany(RewindEvent::class, 'case_id');
    }

    public function fixes(): HasMany
    {
        return $this->hasMany(RewindFix::class, 'case_id');
    }
}
