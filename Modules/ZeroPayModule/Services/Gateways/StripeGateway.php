<?php

namespace Modules\ZeroPayModule\Services\Gateways;

use Modules\ZeroPayModule\Contracts\GatewayContract;
use RuntimeException;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeGateway extends AbstractGateway implements GatewayContract
{
    public function __construct(
        ?array $config = null,
        protected mixed $paymentIntentCreator = null
    ) {
        parent::__construct($config);
    }

    protected function gatewayKey(): string
    {
        return 'stripe';
    }

    public function createPayment(array $session): array
    {
        $this->requireAvailability();

        $payload = [
            'amount' => $this->amountInMinorUnits($session),
            'currency' => strtolower($this->currency($session)),
            'metadata' => array_filter([
                'session_token' => $session['session_token'] ?? null,
                ...$this->metadata($session),
            ], static fn ($value): bool => $value !== null && $value !== ''),
            'automatic_payment_methods' => ['enabled' => true],
        ];

        if (! empty($session['description'])) {
            $payload['description'] = (string) $session['description'];
        }

        if (! empty($session['receipt_email'])) {
            $payload['receipt_email'] = (string) $session['receipt_email'];
        }

        $intent = $this->paymentIntentCreator !== null
            ? ($this->paymentIntentCreator)($payload, $this->gatewayConfig())
            : $this->createStripePaymentIntent($payload);

        $intent = $this->toArray($intent);

        return [
            'status' => 'pending',
            'gateway' => 'stripe',
            'reference' => (string) ($intent['id'] ?? $this->reference($session, 'stripe_')),
            'payment_intent_id' => $intent['id'] ?? null,
            'client_secret' => $intent['client_secret'] ?? null,
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'stripe'];
    }

    public function handleWebhook(array $payload): array
    {
        $rawPayload = (string) ($payload['payload'] ?? $payload['body'] ?? '');
        $signature = (string) ($payload['signature'] ?? $payload['stripe_signature'] ?? '');
        $secret = (string) ($this->gatewayConfig()['webhook_secret'] ?? '');

        if ($rawPayload === '' || $signature === '' || $secret === '') {
            return [
                'processed' => false,
                'validated' => false,
                'gateway' => 'stripe',
                'status' => 'failed',
            ];
        }

        try {
            $event = Webhook::constructEvent($rawPayload, $signature, $secret);
        } catch (SignatureVerificationException|UnexpectedValueException $exception) {
            return [
                'processed' => false,
                'validated' => false,
                'gateway' => 'stripe',
                'status' => 'failed',
                'message' => $exception->getMessage(),
            ];
        }

        $event = $this->toArray($event);
        $object = $event['data']['object'] ?? [];
        $status = $this->statusFromMap($event['type'] ?? null, [
            'PAYMENT_INTENT.SUCCEEDED' => 'completed',
            'PAYMENT_INTENT.PAYMENT_FAILED' => 'failed',
            'PAYMENT_INTENT.CANCELED' => 'failed',
            'PAYMENT_INTENT.PROCESSING' => 'processing',
            'CHARGE.SUCCEEDED' => 'completed',
            'CHARGE.FAILED' => 'failed',
        ]);

        return [
            'processed' => true,
            'validated' => true,
            'gateway' => 'stripe',
            'event_type' => $event['type'] ?? null,
            'reference' => $object['id'] ?? $event['id'] ?? null,
            'status' => $status,
            'raw_payload' => $event,
        ];
    }

    public function calculateFee(float $amount): float
    {
        return round($amount * 0.029 + 0.30, 2);
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'stripe'];
    }

    protected function createStripePaymentIntent(array $payload): array
    {
        $secret = (string) ($this->gatewayConfig()['secret'] ?? '');

        if ($secret === '') {
            throw new RuntimeException('Stripe secret is not configured.');
        }

        $client = new StripeClient($secret);

        return $this->toArray($client->paymentIntents->create($payload));
    }
}
