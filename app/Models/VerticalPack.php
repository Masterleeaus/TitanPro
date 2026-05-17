<?php

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerticalPack extends Model implements TenantAware
{
    use BelongsToTenant, HasFactory;

    protected $fillable = [
        'organization_id',
        'name',
        'slug',
        'description',
        'status',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
