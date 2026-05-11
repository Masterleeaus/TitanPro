<?php

namespace App\Extensions\ProductPhotography\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteVisual extends Model
{
    protected $table = 'ext_quotemaker_visuals';

    protected $fillable = [
        'service_type',
        'site_type',
        'work_area',
        'scope_notes',
        'visual_mode',
        'package_tier',
        'quote_reference',
        'customer_context',
        'generated_prompt',
        'render_payload_json',
        'result_title',
        'result_summary',
        'usage_tag',
        'image_path',
        'status',
    ];
}
