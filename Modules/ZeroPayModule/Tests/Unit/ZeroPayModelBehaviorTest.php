<?php

namespace Modules\ZeroPayModule\Tests\Unit;

use Modules\ZeroPayModule\Models\ZeroPayNotification;
use Modules\ZeroPayModule\Models\ZeroPaySession;
use Modules\ZeroPayModule\ValueObjects\NotificationEventType;
use PHPUnit\Framework\TestCase;

class ZeroPayModelBehaviorTest extends TestCase
{
    public function test_session_relationship_methods_have_expected_return_types(): void
    {
        $transactionsType = (new \ReflectionMethod(ZeroPaySession::class, 'transactions'))->getReturnType();
        $qrCodeType = (new \ReflectionMethod(ZeroPaySession::class, 'qrCode'))->getReturnType();
        $notificationsType = (new \ReflectionMethod(ZeroPaySession::class, 'notifications'))->getReturnType();

        $this->assertSame('Illuminate\Database\Eloquent\Relations\HasMany', $transactionsType?->getName());
        $this->assertSame('Illuminate\Database\Eloquent\Relations\HasOne', $qrCodeType?->getName());
        $this->assertSame('Illuminate\Database\Eloquent\Relations\HasMany', $notificationsType?->getName());
    }

    public function test_session_activity_helpers_follow_status_and_expiry(): void
    {
        $active = new ZeroPaySession([
            'status' => ZeroPaySession::STATUS_PENDING,
        ]);
        $this->assertTrue($active->isActive());
        $this->assertFalse($active->isExpired());

        $expiredByStatus = new ZeroPaySession([
            'status' => ZeroPaySession::STATUS_EXPIRED,
        ]);
        $this->assertTrue($expiredByStatus->isExpired());
        $this->assertFalse($expiredByStatus->isActive());

    }

    public function test_notification_event_type_maps_to_event_enum(): void
    {
        $notification = new ZeroPayNotification([
            'event' => NotificationEventType::SessionCompleted->value,
        ]);

        $this->assertSame(NotificationEventType::SessionCompleted, $notification->event);
        $this->assertSame(NotificationEventType::SessionCompleted, $notification->event_type);

        $notification->event_type = NotificationEventType::Unknown;

        $this->assertSame(NotificationEventType::Unknown, $notification->event);
        $this->assertSame(NotificationEventType::Unknown->value, $notification->getAttributes()['event']);
    }
}
