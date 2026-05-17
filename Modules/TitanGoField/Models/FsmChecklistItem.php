<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;

class FsmChecklistItem extends Model
{
    protected $table = 'fsm_checklist_items';

    protected $fillable = [
        'checklist_template_id', 'label', 'type', 'required', 'sort_order',
    ];

    protected $casts = ['required' => 'boolean'];

    public function template()
    {
        return $this->belongsTo(FsmChecklistTemplate::class, 'checklist_template_id');
    }
}
