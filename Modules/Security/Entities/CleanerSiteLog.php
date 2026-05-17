<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CleanerSiteLog extends BaseModel
{
    use HasCompany;

    protected $table = 'security_cleaner_site_logs';

    protected $guarded = ['id'];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
        'meta' => 'array',
        'forced_checkout' => 'boolean',
    ];

    public function site(): BelongsTo
    {
        return $this->belongsTo(CleanerSite::class, 'site_id');
    }

    public function cleaner(): BelongsTo
    {
        return $this->belongsTo(Cleaner::class, 'cleaner_id');
    }

    public function scopeOpen($query)
    {
        return $query->whereNull('checked_out_at');
    }
}
