<?php

namespace App\Extensions\ProductPhotography\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueBuilder extends Model
{
    protected $table = 'ext_quotemaker_builders';

    protected $fillable = [
        'name',
        'mode',
        'vertical',
        'service_type',
        'variation',
        'theme',
        'visual_mode',
        'package_tier',
        'summary',
        'pricing_model',
        'follow_up_delay_hours',
        'follow_up_channel',
        'auto_create_booking',
        'auto_create_job',
        'auto_send_invoice',
        'auto_negotiate',
        'metadata_json',
        'status',
    ];
}
