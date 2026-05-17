<?php

declare(strict_types=1);

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ThemePack extends Model implements TenantAware
{
    use BelongsToTenant;

    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'description',
        'tokens',
        'preview_image_path',
        'tags',
        'is_public',
    ];

    protected $casts = [
        'tokens' => 'array',
        'tags' => 'array',
        'is_public' => 'boolean',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
