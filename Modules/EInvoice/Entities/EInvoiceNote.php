<?php

namespace Modules\EInvoice\Entities;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;

class EInvoiceNote extends Model
{
    use HasCompany;

    protected $table = 'einvoice_ai_notes';

    protected $fillable = [
        'company_id',
        'invoice_id',
        'user_id',
        'content',
    ];
}
