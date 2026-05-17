<?php

namespace App\Extensions\ProductPhotography\Models;

use Illuminate\Database\Eloquent\Model;

class RevenueExecutionLog extends Model
{
    protected $table = 'ext_quotemaker_execution_logs';

    protected $fillable = [
        'builder_id',
        'mode',
        'action',
        'target_table',
        'target_id',
        'status',
        'payload_json',
        'result_json',
    ];
}
