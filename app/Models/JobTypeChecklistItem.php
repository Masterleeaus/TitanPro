<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobTypeChecklistItem extends Model
{
    use HasFactory;

    protected $fillable = ['job_type_id','task_library_item_id','label','instructions','sort_order','is_required','requires_photo','condition_type','condition_value'];

    protected function casts(): array
    {
        return ['is_required' => 'boolean','requires_photo' => 'boolean','sort_order' => 'integer'];
    }

    public function jobType(): BelongsTo { return $this->belongsTo(JobType::class); }
    public function taskLibraryItem(): BelongsTo { return $this->belongsTo(JobChecklistItem::class, 'task_library_item_id'); }
}
