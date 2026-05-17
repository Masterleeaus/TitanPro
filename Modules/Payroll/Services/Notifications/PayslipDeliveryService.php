<?php

namespace Modules\Payroll\Services\Notifications;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Modules\Payroll\Contracts\Services\PayslipAccessLinkServiceContract;
use Modules\Payroll\Contracts\Services\PayslipDeliveryAuditServiceContract;
use Modules\Payroll\Contracts\Services\PayslipDeliveryServiceContract;
use Modules\Payroll\Contracts\Services\PayslipPreferenceServiceContract;
use Modules\Payroll\Notifications\Mail\PayslipCreatedNotification;
use Modules\Payroll\Support\DTOs\PayslipDeliveryResult;
use Modules\Payroll\Support\DTOs\PayslipDocument;

class PayslipDeliveryService implements PayslipDeliveryServiceContract
{
    public function deliver(PayslipDocument $document, array $employee = [], array $options = []): PayslipDeliveryResult
    {
        $preferences = app(PayslipPreferenceServiceContract::class)->getForEmployee($document->userId);
        $enabled = (bool) ($options['send'] ?? $preferences['enabled'] ?? config('payroll.features.send_payslips_to_employees', true));
        $recipient = $employee['email'] ?? $employee['work_email'] ?? $employee['user']['email'] ?? null;
        $channels = $options['channels'] ?? $preferences['channels'] ?? config('payroll.notifications.payslip_created', ['mail', 'database']);
        $deliveryId = app(PayslipDeliveryAuditServiceContract::class)->recordAttempt($document, $employee, ['channels' => $channels] + $options);
        $accessUrl = app(PayslipAccessLinkServiceContract::class)->temporaryLink($document, $employee, $options);

        if ($accessUrl) {
            $payload = $document->payload + ['access_url' => $accessUrl];
            $document = new PayslipDocument($document->userId, $document->periodFrom, $document->periodTo, $document->html, $payload, $document->storagePath);
        }

        if (! $enabled) {
            $result = new PayslipDeliveryResult($document->userId, 'skipped', $channels, $recipient, 'Payslip delivery disabled.');
            app(PayslipDeliveryAuditServiceContract::class)->recordResult($deliveryId, $result);
            return $result;
        }

        if (! $recipient && ! isset($employee['notifiable'])) {
            Log::warning('Payroll payslip delivery skipped: missing employee email.', [
                'user_id' => $document->userId,
                'period_from' => $document->periodFrom,
                'period_to' => $document->periodTo,
            ]);
            $result = new PayslipDeliveryResult($document->userId, 'failed', $channels, null, 'Missing employee email address.');
            app(PayslipDeliveryAuditServiceContract::class)->recordResult($deliveryId, $result);
            return $result;
        }

        $notifiable = $employee['notifiable'] ?? Notification::route('mail', $recipient);
        $notifiable->notify(new PayslipCreatedNotification($document, $options['company'] ?? []));

        $result = new PayslipDeliveryResult($document->userId, 'delivered', $channels, $recipient, null, [
            'delivery_id' => $deliveryId,
            'access_url' => $accessUrl,
            'period_from' => $document->periodFrom,
            'period_to' => $document->periodTo,
        ]);
        app(PayslipDeliveryAuditServiceContract::class)->recordResult($deliveryId, $result);
        return $result;
    }
}
