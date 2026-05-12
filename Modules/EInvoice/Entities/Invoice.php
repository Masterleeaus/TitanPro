<?php

namespace Modules\EInvoice\Entities;

use App\Traits\HasCompany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasCompany;

    protected $table = 'einvoice_invoices';

    protected $fillable = [
        'company_id', 'client_id', 'currency', 'status', 'due_date', 'notes',
        'subtotal', 'tax_total', 'grand_total',
    ];

    protected $casts = [
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'invoice_id');
    }

    public function aiNotes(): HasMany
    {
        return $this->hasMany(EInvoiceNote::class, 'invoice_id');
    }
}
