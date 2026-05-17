<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class FsmChecklistTemplate extends Model
{
    protected $table = 'fsm_checklist_templates';

    protected $fillable = ['company_id', 'name', 'vertical', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeTenant(Builder $query, int $companyId): Builder
    {
        return $query->where('company_id', $companyId);
    }

    public function items()
    {
        return $this->hasMany(FsmChecklistItem::class, 'checklist_template_id')->orderBy('sort_order');
    }
}
