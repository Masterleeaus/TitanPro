<?php

namespace Modules\ZeroPayModule\ValueObjects;

enum NotificationEventType: string
{
    case SessionCreated = 'session.created';
    case SessionOpened = 'session.opened';
    case SessionPending = 'session.pending';
    case SessionProcessing = 'session.processing';
    case SessionCompleted = 'session.completed';
    case SessionFailed = 'session.failed';
    case SessionExpiring = 'session.expiring';
    case SessionExpired = 'session.expired';
    case PaymentStarted = 'payment.started';
    case PaymentPending = 'payment.pending';
    case PaymentCompleted = 'payment.completed';
    case PaymentFailed = 'payment.failed';
    case Unknown = 'unknown';
}
