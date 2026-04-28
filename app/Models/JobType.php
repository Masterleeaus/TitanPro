<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobType extends Model
{
    use HasFactory;

    protected $fillable = ['organization_id','name','color','description','is_active','service_category','default_price','default_duration_minutes','recommended_team_size','allows_recurring','requires_quality_check','required_equipment'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean','default_price' => 'decimal:2','default_duration_minutes' => 'integer','recommended_team_size' => 'integer','allows_recurring' => 'boolean','requires_quality_check' => 'boolean'];
    }

    public function organization(): BelongsTo { return $this->belongsTo(Organization::class); }
    public function jobs(): HasMany { return $this->hasMany(Job::class); }
    public function checklistItems(): HasMany { return $this->hasMany(JobTypeChecklistItem::class)->orderBy('sort_order'); }
}
