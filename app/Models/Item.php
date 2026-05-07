<?php

namespace App\Models;

use App\Contracts\TenantAware;
use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model implements TenantAware
{
    use BelongsToTenant, HasFactory, SoftDeletes;

    protected $fillable = ['organization_id', 'name', 'sku', 'description', 'unit_price', 'unit', 'is_taxable', 'is_active', 'category', 'pricing_type', 'estimated_minutes', 'is_upsell', 'injects_checklist_tasks'];

    protected function casts(): array
    {
        return ['unit_price' => 'decimal:2', 'is_taxable' => 'boolean', 'is_active' => 'boolean', 'estimated_minutes' => 'integer', 'is_upsell' => 'boolean', 'injects_checklist_tasks' => 'boolean'];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function attachableServices(): BelongsToMany
    {
        return $this->belongsToMany(JobType::class, 'item_job_type')->withTimestamps();
    }

    public static function pricingTypeLabels(): array
    {
        return [
            'flat' => 'Flat',
            'per_m2' => 'Per m²',
            'per_unit' => 'Per unit',
            'fixed' => 'Fixed price (legacy)',
            'per_sqm' => 'Per m² (legacy)',
            'quantity' => 'Per unit (legacy)',
            'per_room' => 'Per room (legacy)',
            'per_bathroom' => 'Per bathroom (legacy)',
            'per_hour' => 'Per hour (legacy)',
        ];
    }

    public static function pricingTypeLabel(?string $pricingType): string
    {
        return match ($pricingType) {
            'flat', 'fixed' => 'Flat',
            'per_m2', 'per_sqm' => 'Per m²',
            'per_unit', 'quantity' => 'Per unit',
            'per_room' => 'Per room',
            'per_bathroom' => 'Per bathroom',
            'per_hour' => 'Per hour',
            default => (string) $pricingType,
        };
    }
}
