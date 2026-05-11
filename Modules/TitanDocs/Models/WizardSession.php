<?php

namespace Modules\TitanDocs\Models;

use Illuminate\Database\Eloquent\Model;

class WizardSession extends Model
{
    protected $table = 'titandocs_wizard_sessions';

    protected $fillable = [
        'company_id',
        'user_id',
        'doc_kind',
        'current_step',
        'status',
        'payload_json',
    ];

    protected $casts = [
        'payload_json' => 'array',
    ];
}
