<?php

namespace App\Extensions\TitanLeads\System\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceFollowup extends Model
{
    protected $table = 'ext_invoice_followups';

    protected $fillable = [
        'user_id',
        'invoice_ref',
        'contact_id',
        'customer_phone',
        'customer_email',
        'amount_due',
        'currency',
        'due_date',
        'status',
        'last_reminded_at',
        'next_followup_at',
        'rules',
    ];

    protected $casts = [
        'due_date' => 'date',
        'last_reminded_at' => 'datetime',
        'next_followup_at' => 'datetime',
        'rules' => 'json',
    ];
}
