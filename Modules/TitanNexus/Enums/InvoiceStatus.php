<?php
namespace Modules\TitanNexus\Enums;

enum InvoiceStatus: string
{
    case DRAFT = 'draft';
    case SENT = 'sent';
    case DUE = 'due';
    case OVERDUE = 'overdue';
    case PAYMENT_PLAN = 'payment_plan';
    case PAID = 'paid';
    case WRITTEN_OFF = 'written_off';
}
