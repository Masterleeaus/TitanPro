<?php

namespace Modules\ZeroFussPortal\Models;

use Illuminate\Database\Eloquent\Model;

class PortalFeedback extends Model
{
    protected $table = 'zerofuss_feedback';

    protected $fillable = [
        'company_id',
        'customer_id',
        'rating',
        'message',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
