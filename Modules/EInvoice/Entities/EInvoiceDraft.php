<?php

namespace Modules\EInvoice\Entities;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class EInvoiceDraft extends Model
{
    use HasCompany;

    protected $table = 'einvoice_ai_drafts';

    protected $fillable = [
        'company_id',
        'user_id',
        'client_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'array',
    ];
}
