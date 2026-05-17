<?php

declare(strict_types=1);

namespace Modules\Dispatch\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Dispatch\Models\Traits\BelongsToTenant;

class DispatchChecklistItem extends Model
{
    use BelongsToTenant;

    protected $table = 'dispatch_checklist_items';

    protected $fillable = ['company_id', 'checklist_id', 'label', 'instructions', 'required', 'completed', 'completed_by', 'completed_at', 'sort_order', 'metadata'];

    protected function casts(): array
    {
        return ['required' => 'boolean', 'completed' => 'boolean', 'completed_at' => 'datetime', 'metadata' => 'array'];
    }

    public function checklist()
    {
        return $this->belongsTo(DispatchChecklist::class, 'checklist_id');
    }
}
