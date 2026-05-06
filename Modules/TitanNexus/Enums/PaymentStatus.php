<?php
namespace Modules\TitanNexus\Enums;

enum PaymentStatus: string
{
    case PENDING = 'pending';
    case LINK_SENT = 'link_sent';
    case PROCESSING = 'processing';
    case PAID = 'paid';
    case FAILED = 'failed';
    case REFUNDED = 'refunded';
}
