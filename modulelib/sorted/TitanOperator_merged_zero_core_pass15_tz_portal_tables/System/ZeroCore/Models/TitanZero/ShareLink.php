<?php

namespace App\Extensions\TitanOperator\System\ZeroCore\Models\TitanZero;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'category',
        'chat',
        'message',
        'time',
    ];
}
