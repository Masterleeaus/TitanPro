<?php

namespace Modules\InstantAds\Entities;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class InstantAdsBrandKit extends Model
{
    use HasCompany;
    protected $table = 'instant_ads_brand_kits';

    protected $fillable = [
        'company_id',
        'name',
        'primary_color',
        'secondary_color',
        'logo_path',
        'tagline',
        'is_default',
        'created_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeForCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }
}
