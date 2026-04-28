<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Awcodes\Curator\Models\Media as CuratorMedia;

class Media extends CuratorMedia
{
    use HasUuids;
    public function team(): BelongsTo
    {
        return $this->belongsTo(team::class);
    }
}
