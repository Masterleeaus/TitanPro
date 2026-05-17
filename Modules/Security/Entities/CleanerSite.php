<?php

namespace Modules\Security\Entities;

use App\Models\BaseModel;
use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CleanerSite extends BaseModel
{
    use HasCompany;

    protected $table = 'security_cleaner_sites';

    protected $guarded = ['id'];

    protected $casts = [
        'meta' => 'array',
        'active' => 'boolean',
    ];

    public function cleaners(): HasMany
    {
        return $this->hasMany(Cleaner::class, 'site_id');
    }

    public function siteLogs(): HasMany
    {
        return $this->hasMany(CleanerSiteLog::class, 'site_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
