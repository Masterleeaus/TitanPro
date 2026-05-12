<?php

namespace Modules\TitanGoField\Models;

use Illuminate\Database\Eloquent\Model;

class FieldJobChecklistResponse extends Model
{
    protected $table = 'field_job_checklist_responses';

    protected $fillable = [
        'checklist_run_id', 'checklist_item_id', 'value', 'file_path', 'passed',
    ];

    protected $casts = ['passed' => 'boolean'];

    public function run()
    {
        return $this->belongsTo(FieldJobChecklistRun::class, 'checklist_run_id');
    }
}
