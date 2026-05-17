<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class DispatchChecklist extends Model
{
    use BelongsToTenant;

    protected $table = 'dispatch_checklists';

    protected $fillable = ['company_id', 'work_order_id', 'name', 'status', 'completed_by', 'completed_at', 'metadata'];

    protected function casts(): array
    {
        return ['completed_at' => 'datetime', 'metadata' => 'array'];
    }

    public function workOrder()
    {
        return $this->belongsTo(DispatchWorkOrder::class, 'work_order_id');
    }

    public function items()
    {
        return $this->hasMany(DispatchChecklistItem::class, 'checklist_id')->orderBy('sort_order');
    }
}
