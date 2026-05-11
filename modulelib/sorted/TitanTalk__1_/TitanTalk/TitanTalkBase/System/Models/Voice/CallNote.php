<?php

namespace App\Extensions\MarketingBot\System\Models\Voice;

use Illuminate\Database\Eloquent\Model;

class CallNote extends Model
{
    protected $table = 'titantalk_call_notes';

    protected $fillable = [
        'call_id',
        'user_id',
        'note',
    ];

    protected $casts = [
        'call_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function call()
    {
        return $this->belongsTo(Call::class, 'call_id');
    }
}
