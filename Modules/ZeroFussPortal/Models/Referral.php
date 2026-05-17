<?php

namespace Modules\ZeroFussPortal\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'zerofuss_referrals';

    protected $fillable = [
        'company_id',
        'customer_id',
        'referral_code',
        'referred_email',
        'referred_name',
        'status',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];
}
